<?php

namespace App\Policies\Client;

use App\Models\PurchaseOrder;
use App\Models\CompanyUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseOrderPolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, PurchaseOrder $purchaseOrder): bool
    {
        return true;
    }
}
