<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicantDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function getUniversities($range = null, $startDate = null, $endDate = null)
    {
        $query = DB::table('education')
            ->select('university', DB::raw('COUNT(*) as count'))
            ->whereNotNull('university') // Ensure we only count valid university entries
            ->groupBy('university');

        if ($range && $range !== 'all') {
            $days = intval($range);
            $query->where('created_at', '>=', now()->subDays($days));
        } elseif ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->get();
    }

    public function filterByDateRange($range = null, $startDate = null, $endDate = null)
    {
        $query = ApplicantDetails::with('jobPosition')
            ->whereNotNull('created_at');

        if ($range && $range !== 'all') {
            $days = intval($range);
            $query->where('created_at', '>=', now()->subDays($days));
        } elseif ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->get();
    }

    public function updateData(Request $request)
    {
        try {
            $range = $request->input('range');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            Log::info('Dashboard update request received', [
                'range' => $range,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);

            $applicants = $this->filterByDateRange($range, $startDate, $endDate);
            $universities = $this->getUniversities($range, $startDate, $endDate);
            $grouped = $applicants->groupBy(function ($applicant) {
                return $applicant->jobPosition->application_type ?? 'Unknown';
            });

            $result = [];
            foreach (['Intern', 'Full-Time Employee', 'Part-Time Employee'] as $type) {
                $group = $grouped[$type] ?? collect();

                $avgDays = $group->avg(function ($applicant) {
                    $hiringDate = $applicant->hiring_date ? \Carbon\Carbon::parse($applicant->hiring_date) : null;
                    $createdAt = $applicant->created_at ? \Carbon\Carbon::parse($applicant->created_at) : null;

                    if ($hiringDate && $createdAt) {
                        return $createdAt->diffInDays($hiringDate);
                    }
                    return null;
                });

                $hiredCount = $group->where('status', 'Hired')->count();

                $result[$type] = [
                    'count' => $group->count(),
                    'avg_days' => round($avgDays ?? 0),
                    'hired_count' => $hiredCount,
                ];
            }

            $totalApplications = $applicants->count();

            $response = [
                'totalApplications' => $totalApplications,
                'interns' => $result['Intern'],
                'fullTime' => $result['Full-Time Employee'],
                'partTime' => $result['Part-Time Employee'],
                'partTimePositions' => $applicants->where('jobPosition.application_type', 'Part-Time Employee')
                    ->groupBy('jobPosition.position_title')
                    ->map(function ($group) {
                        return (object) [
                            'count' => $group->count(),
                            'jobPosition' => (object) ['position_title' => $group->first()->jobPosition->position_title]
                        ];
                    })->values(),
                'fullTimePositions' => $applicants->where('jobPosition.application_type', 'Full-Time Employee')
                    ->groupBy('jobPosition.position_title')
                    ->map(function ($group) {
                        return (object) [
                            'count' => $group->count(),
                            'jobPosition' => (object) ['position_title' => $group->first()->jobPosition->position_title]
                        ];
                    })->values(),
                'internPositions' => $applicants->where('jobPosition.application_type', 'Intern')
                    ->groupBy('jobPosition.position_title')
                    ->map(function ($group) {
                        return (object) [
                            'count' => $group->count(),
                            'jobPosition' => (object) ['position_title' => $group->first()->jobPosition->position_title]
                        ];
                    })->values(),
                'universities' => $universities
            ];

            Log::info('Dashboard update response', $response);
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Dashboard update failed: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            return response()->json([
                'error' => 'An error occurred while updating the dashboard.'
            ], 500);
        }
    }
    public function dashboard(Request $request)
    {
        $range = $request->input('range');
        $university = $request->input('university');
        $applicants = $this->filterByDateRange($range, null, null);

        $grouped = $applicants->groupBy(function ($applicant) {
            return $applicant->jobPosition->application_type ?? 'Unknown';
        });

        $result = [];
        foreach (['Intern', 'Full-Time Employee', 'Part-Time Employee'] as $type) {
            $group = $grouped[$type] ?? collect();

            $avgDays = $group->avg(function ($applicant) {
                $hiringDate = $applicant->hiring_date ? \Carbon\Carbon::parse($applicant->hiring_date) : null;
                $createdAt = $applicant->created_at ? \Carbon\Carbon::parse($applicant->created_at) : null;

                if ($hiringDate && $createdAt) {
                    return $createdAt->diffInDays($hiringDate);
                }
                return null;
            });

            // Count the number of hired applicants for the specific type
            $hiredCount = $group->where('status', 'Hired')->count();

            $result[$type] = [
                'count' => $group->count(),
                'avg_days' => round($avgDays ?? 0),
                'hired_count' => $hiredCount,  // Added hired count
            ];
        }

        $totalApplications = $applicants->count();

        // For Applicant Status
        $statusTemplate = [
            'For Screening' => 0,
            'Shortlisted' => 0,
            'For Interview' => 0,
            'Scheduled for Interview' => 0,
            'Completed Interview' => 0,
            'Offer Made' => 0,
            'Hired' => 0,
            'Rejected' => 0
        ];

        $stages = $statusTemplate;
        $employeeApplicants = $statusTemplate;
        $partEmployeeApplicants = $statusTemplate;
        $internApplicants = $statusTemplate;

        // Get status counts for each applicant type
        // Get status counts for all applicants
        $statusCounts = ApplicantDetails::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Populate status counts
        foreach ($statusCounts as $status => $count) {
            if (isset($stages[$status])) {
                $stages[$status] = $count;
            }
        }

        $statusInternCounts = ApplicantDetails::select('status', DB::raw('COUNT(*) as count'))
            ->whereHas('jobPosition', function ($query) {
                $query->whereIn('application_type', ['Intern']);
            })
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Populate status counts
        foreach ($statusInternCounts as $status => $count) {
            if (isset($internApplicants[$status])) {
                $internApplicants[$status] = $count;
            }
        }

        $statusEmployeeCounts = ApplicantDetails::select('status', DB::raw('COUNT(*) as count'))
            ->whereHas('jobPosition', function ($query) {
                $query->whereIn('application_type', ['Full-Time Employee']);
            })
            ->when($range && $range !== 'all', function ($query) use ($range) {
                $days = intval($range);
                $query->where('created_at', '>=', now()->subDays($days));
            })
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Populate status counts
        foreach ($statusEmployeeCounts as $status => $count) {
            if (isset($employeeApplicants[$status])) {
                $employeeApplicants[$status] = $count;
            }
        }

        $statusPartEmployeeCounts = ApplicantDetails::select('status', DB::raw('COUNT(*) as count'))
            ->whereHas('jobPosition', function ($query) {
                $query->whereIn('application_type', ['Part-Time Employee']);
            })
            ->when($range && $range !== 'all', function ($query) use ($range) {
                $days = intval($range);
                $query->where('created_at', '>=', now()->subDays($days));
            })
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Populate status counts
        foreach ($statusPartEmployeeCounts as $status => $count) {
            if (isset($partEmployeeApplicants[$status])) {
                $partEmployeeApplicants[$status] = $count;
            }
        }

        // Get employee applications by position
        $fullTimePositions = ApplicantDetails::with('jobPosition')
            ->whereHas('jobPosition', function ($query) {
                $query->where('application_type', 'Full-Time Employee');
            })

            ->select('position_id', DB::raw('COUNT(*) as count'))
            ->groupBy('position_id')
            ->with(['jobPosition' => function ($query) {
                $query->select('id', 'position_title');
            }])
            ->get();



        $partTimePositions = ApplicantDetails::with('jobPosition')
            ->whereHas('jobPosition', function ($query) {
                $query->where('application_type', 'Part-Time Employee');
            })

            ->select('position_id', DB::raw('COUNT(*) as count'))
            ->groupBy('position_id')
            ->with(['jobPosition' => function ($query) {
                $query->select('id', 'position_title');
            }])
            ->get();

        $internPositions = ApplicantDetails::with('jobPosition')
            ->whereHas('jobPosition', function ($query) {
                $query->where('application_type', 'Intern');
            })

            ->select('position_id', DB::raw('COUNT(*) as count'))
            ->groupBy('position_id')
            ->get();


        return view('dashboard', [
            'universities' => $this->getUniversities(),
            'internApplicants' => $internApplicants,
            'stages' => $stages,
            'partEmployeeApplicants' => $partEmployeeApplicants,
            'employeeApplicants' => $employeeApplicants,
            'totalApplications' => $totalApplications,
            'interns' => $result['Intern'],
            'fullTime' => $result['Full-Time Employee'],
            'partTime' => $result['Part-Time Employee'],
            'fullTimePositions' => $fullTimePositions,
            'partTimePositions' => $partTimePositions,
            'internPositions' => $internPositions,
        ]);
    }
}
