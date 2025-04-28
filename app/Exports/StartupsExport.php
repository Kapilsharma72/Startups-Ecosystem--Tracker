<?php

namespace App\Exports;

use App\Models\Startup;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StartupsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Startup::with('investors')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Website',
            'Location',
            'Industry',
            'Stage',
            'Funding (in millions)',
            'Funding Round',
            'Founded',
            'Employees',
            'Growth',
            'Technologies',
            'Description',
            'Investors',
            'Created At',
            'Updated At'
        ];
    }

    /**
     * @param Startup $startup
     * @return array
     */
    public function map($startup): array
    {
        // Format investors as a comma-separated list
        $investors = $startup->investors->pluck('name')->implode(', ');
        
        // Format technologies (could be stored as array or comma-separated string)
        $technologies = $startup->technologies;
        if (is_array($technologies)) {
            $technologies = implode(', ', $technologies);
        }

        return [
            $startup->id,
            $startup->name,
            $startup->website,
            $startup->location,
            $startup->industry,
            $startup->stage,
            $startup->funding,
            $startup->funding_round,
            $startup->founded,
            $startup->emp_numbers,
            $startup->growth . '%',
            $technologies,
            $startup->description,
            $investors,
            $startup->created_at,
            $startup->updated_at
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (header row)
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4B5563']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }
}
