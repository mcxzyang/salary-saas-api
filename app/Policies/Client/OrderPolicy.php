<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, Order $order): bool
    {
        return $user->company_id === $order->company_id;
    }
}
