<?php

namespace App\Policies\Client;

use App\Models\CompanySetting;
use App\Models\CompanyUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanySettingPolicy
{
    use HandlesAuthorization;

    public function own(CompanyUser $user, CompanySetting $companySetting): bool
    {
        return $user->company_id === $companySetting->company_id;
    }
}
