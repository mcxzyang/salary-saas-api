<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyUserPolicy
{
    use HandlesAuthorization;

    public function own(CompanyUser $user, CompanyUser $companyUser): bool
    {
        return $user->company_id === $companyUser->company_id;
    }
}
