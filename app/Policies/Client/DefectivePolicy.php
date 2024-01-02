<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use App\Models\Defective;
use Illuminate\Auth\Access\HandlesAuthorization;

class DefectivePolicy
{
    use HandlesAuthorization;

    public function own(CompanyUser $user, Defective $defective): bool
    {
        return $user->company_id === $defective->company_id;
    }

}
