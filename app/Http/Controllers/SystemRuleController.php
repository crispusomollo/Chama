<?php

namespace App\Http\Controllers;

use App\Models\SystemRule;
use Illuminate\Http\Request;

class SystemRuleController extends Controller
{
    public function index()
    {
        $rules = SystemRule::orderByDesc(
            'effective_from'
        )->paginate(20);

        return view(
            'rules.index',
            compact('rules')
        );
    }

    public function create()
    {
        return view('rules.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([

            'effective_from' => 'required|date',

            'monthly_contribution' =>
                'required|numeric|min:1',

            'shares_allocation' =>
                'required|numeric|min:0',

            'insurance_allocation' =>
                'required|numeric|min:0',

            'penalty_amount' =>
                'required|numeric|min:0',

            'grace_days' =>
                'required|integer|min:0',

            'interest_rate' =>
                'required|numeric|min:0',

            'loan_multiplier' =>
                'required|integer|min:1',

            'max_repayment_months' =>
                'required|integer|min:1',

            'notes' =>
                'nullable|string',
        ]);

        $data['active'] = true;

        SystemRule::create($data);

        return redirect()
            ->route('rules.index')
            ->with(
                'success',
                'Policy created successfully.'
            );
    }

    public function edit(SystemRule $rule)
    {
        return view(
            'rules.edit',
            compact('rule')
        );
    }

    public function update(
        Request $request,
        SystemRule $rule
    ) {
        $data = $request->validate([

            'effective_from' => 'required|date',

            'monthly_contribution' =>
                'required|numeric|min:1',

            'shares_allocation' =>
                'required|numeric|min:0',

            'insurance_allocation' =>
                'required|numeric|min:0',

            'penalty_amount' =>
                'required|numeric|min:0',

            'grace_days' =>
                'required|integer|min:0',

            'interest_rate' =>
                'required|numeric|min:0',

            'loan_multiplier' =>
                'required|integer|min:1',

            'max_repayment_months' =>
                'required|integer|min:1',

            'notes' =>
                'nullable|string',

            'active' =>
                'nullable|boolean',
        ]);

        $rule->update($data);

        return back()->with(
            'success',
            'Policy updated.'
        );
    }
}
