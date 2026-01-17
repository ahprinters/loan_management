<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Loan;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public function render()
    {
        $customersCount = Customer::query()
            ->where('user_id', auth::id())
            ->count();

        $baseLoans = Loan::query()->where('user_id', auth::id());

        $loansCount = (clone $baseLoans)->count();

        // These work once the loans table has the suggested columns.
        $totalLoanAmount = (clone $baseLoans)->sum('amount');
        $activeLoans = (clone $baseLoans)->where('status', 'active')->count();
        $overdueLoans = (clone $baseLoans)
            ->whereNotNull('due_date')
            ->where('due_date', '<', Carbon::today())
            ->whereIn('status', ['active', 'overdue'])
            ->count();

        return view('livewire.dashboard', compact(
            'customersCount',
            'loansCount',
            'totalLoanAmount',
            'activeLoans',
            'overdueLoans'
        ));
    }
}
