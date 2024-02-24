<?php

namespace App\Policies\Client;

use App\Models\PurchasePlan;
use App\Models\CompanyUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchasePlanPolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, PurchasePlan $purchasePlan): bool
    {
        return true;
    }
}
