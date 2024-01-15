<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use App\Models\PerformanceRule;
use Illuminate\Auth\Access\HandlesAuthorization;

class PerformanceRulePolicy
{
    use HandlesAuthorization;

    public function own(CompanyUser $user, PerformanceRule $performanceRule): bool
    {
        return $user->company_id === $performanceRule->company_id;
    }
}
