<?php

namespace App\Exports;

use App\Models\Contribution;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContributionsExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Contribution::with('borrower');

        /*
        |--------------------------------------------------------------------------
        | FILTERS
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['borrower_id'])) {

            $query->where(
                'borrower_id',
                $this->filters['borrower_id']
            );
        }

        if (!empty($this->filters['status'])) {

            $query->where(
                'status',
                $this->filters['status']
            );
        }

        if (!empty($this->filters['type'])) {

            $query->where(
                'type',
                $this->filters['type']
            );
        }

        if (!empty($this->filters['month'])) {

            $query->where(
                'period',
                $this->filters['month']
            );
        }

        if (
            !empty($this->filters['from'])
            &&
            !empty($this->filters['to'])
        ) {

            $query->whereBetween(
                'contribution_date',
                [
                    $this->filters['from'],
                    $this->filters['to']
                ]
            );
        }

        return $query->get()->map(function ($c) {

            return [

                'Member' => $c->borrower->full_name,

                'Amount' => $c->amount,

                'Type' => $c->type,

                'Status' => $c->status,

                'Contribution Date' => $c->contribution_date,

                'Period' => $c->period,

                'Created At' => $c->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [

            'Member',
            'Amount',
            'Type',
            'Status',
            'Contribution Date',
            'Period',
            'Created At',
        ];
    }
}
