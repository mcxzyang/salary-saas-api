<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use App\Models\WorkingTechnology;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkingTechnologyPolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, WorkingTechnology $workingTechnology): bool
    {
        return $user->company_id === $workingTechnology->company_id;
    }
}
