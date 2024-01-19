<?php

namespace App\Policies\Admin;

use App\Models\CompanyOption;
use App\Models\CompanyUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyOptionPolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, CompanyOption $companyOption): bool
    {
        return true;
    }
}
