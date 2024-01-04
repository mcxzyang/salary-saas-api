<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use App\Models\Workorder;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkorderPolicy
{
    use HandlesAuthorization;

    public function own(CompanyUser $user, Workorder $workorder): bool
    {
        return $user->company_id === $workorder->company_id;
    }

}
