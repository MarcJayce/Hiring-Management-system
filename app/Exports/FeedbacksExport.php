<?php

namespace App\Exports;

use App\Models\Feedback;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FeedbacksExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Feedback::with('interview.interviewer')->get();
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], 
        ];
    }


    public function headings(): array
    {
        return ['Interview Round', 'Date', 'Time', 'Name', 'Feedback / Comments', 'Interviewer'];
    }

    public function map($feedback): array
    {
        return [
            $feedback->interview->round,
            $feedback->feedback_date,
            $feedback->interview->time,
            $feedback->feedback_name,
            $feedback->feedback_text,
            optional($feedback->interview->interviewer)->name,
        ];
    }
}
