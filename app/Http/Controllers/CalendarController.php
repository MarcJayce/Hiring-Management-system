<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar');
    }

    public function fetchEvents()
    {
        // Join with applicant_details and users tables to get names
        $interviews = DB::table('interview_schedules')
            ->where('interview_schedules.status', 'Pending')
            ->join('applicant_details', 'interview_schedules.applicant_id', '=', 'applicant_details.id')
            ->join('users', 'interview_schedules.user_id', '=', 'users.id')
            ->select(
                'interview_schedules.id',
                'interview_schedules.applicant_id',
                'interview_schedules.type',
                'interview_schedules.date',
                'interview_schedules.time',
                'interview_schedules.duration',
                'interview_schedules.location',
                'interview_schedules.user_id',
                'interview_schedules.round',
                'interview_schedules.status',
                'interview_schedules.created_at',
                'interview_schedules.updated_at',
                'applicant_details.first_name as applicant_first_name',
                'applicant_details.last_name as applicant_last_name',
                'users.name as interviewer_name'
            )
            ->get();

        $events = [];

        foreach ($interviews as $interview) {
            // Format the date and time properly for the calendar
            $date = $interview->date;
            $time = $interview->time;

            // Create ISO format date-time string
            $startDateTime = date('Y-m-d\TH:i:s', strtotime("$date $time"));

            // Create applicant name
            $applicantName = $interview->applicant_first_name . ' ' . $interview->applicant_last_name;

            $events[] = [
                'id' => $interview->id,
                'title' => $applicantName,
                'start' => $startDateTime,
                'color' => '#7b2cbf',
                'extendedProps' => [
                    'applicant_id' => $interview->applicant_id,
                    'applicant_name' => $applicantName,
                    'type' => $interview->type,
                    'duration' => $interview->duration,
                    'location' => $interview->location,
                    'interviewer_id' => $interview->user_id,
                    'interviewer_name' => $interview->interviewer_name,
                    'round' => $interview->round,
                    'status' => $interview->status,
                    'created_at' => $interview->created_at,
                    'updated_at' => $interview->updated_at
                ]
            ];
        }

        return response()->json($events);
    }
}
