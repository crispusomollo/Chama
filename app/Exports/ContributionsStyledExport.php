<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ContributionsStyledExport implements FromView
{
    protected $contributions;

    public function __construct($contributions)
    {
        $this->contributions = $contributions;
    }

    public function view(): View
    {
        return view(
            'contributions.exports.excel',
            [
                'contributions' => $this->contributions
            ]
        );
    }
}
