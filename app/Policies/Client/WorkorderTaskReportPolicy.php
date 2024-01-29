<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use App\Models\WorkorderTaskReport;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkorderTaskReportPolicy
{
    use HandlesAuthorization;

    public function own(CompanyUser $user, WorkorderTaskReport $workorderTaskReport): bool
    {
        return $user->id === $workorderTaskReport->approve_company_user_id;
    }
}
