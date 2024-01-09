<?php

namespace App\Policies;

use App\Models\CompanyUser;
use App\Models\StateFactory;
use Illuminate\Auth\Access\HandlesAuthorization;

class StateFactoryPolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, StateFactory $stateFactory): bool
    {
        return $user->company_id === $stateFactory->company_id;
    }

}
