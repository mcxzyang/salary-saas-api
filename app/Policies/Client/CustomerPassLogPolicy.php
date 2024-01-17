<?php

namespace App\Policies\Client;

use App\Models\CustomerPassLog;
use App\Models\CompanyUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPassLogPolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, CustomerPassLog $customerPassLog): bool
    {
        return $user->company_id === $customerPassLog->company_id;
    }
}
