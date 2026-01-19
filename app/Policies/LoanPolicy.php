<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Loan;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoanPolicy
{

use HandlesAuthorization;
/**
     * Create a new policy instance.
     */
    public function viewAny(User $user): bool    {
        return true; // Authenticated users can view their loans

    }

    public function view( User $user, Loan $loan)
    {
        return $user->id === $loan->user_id;// user can update only their
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Loan $loan): bool
    {
    return (int) $loan->user_id === (int) $user->id;

    }

    public function delete(User $user, Loan $loan)
    {
        return (int) $loan->user_id === (int) $user->id; // User can delete only their

    }
}
