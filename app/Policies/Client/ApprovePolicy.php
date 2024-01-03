<?php

namespace App\Policies\Client;

use App\Models\Approve;
use App\Models\CompanyUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApprovePolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, Approve $approve): bool
    {
        return $user->company_id === $approve->company_id;
    }

}
