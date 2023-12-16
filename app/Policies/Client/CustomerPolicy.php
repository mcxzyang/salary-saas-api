<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use App\Models\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, Customer $customer): bool
    {
        return $user->company_id === $customer->company_id;
    }
}
