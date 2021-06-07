<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(User $user)
    {
        return $user->role === User::ADMIN;
    }

    public function manageOrganization(User $user, Organization $organization) 
    {
        return ($user->role === User::ADMIN) || ($user->role === User::ACCOUNT_MANAGER && $user->organization_id === $organization->id);
    }
}
