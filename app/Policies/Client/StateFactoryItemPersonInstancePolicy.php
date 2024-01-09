<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use App\Models\StateFactoryItemPersonInstance;
use Illuminate\Auth\Access\HandlesAuthorization;

class StateFactoryItemPersonInstancePolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, StateFactoryItemPersonInstance $stateFactoryItemPersonInstance): bool
    {
        return $user->company_id === $stateFactoryItemPersonInstance->company_id;
    }
}
