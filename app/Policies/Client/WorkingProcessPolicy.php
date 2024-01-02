<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use App\Models\WorkingProcess;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkingProcessPolicy
{
    use HandlesAuthorization;

    public function own(CompanyUser $user, WorkingProcess $workingProcess): bool
    {
        return $user->company_id === $workingProcess->company_id;
    }
}
