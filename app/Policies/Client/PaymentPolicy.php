<?php

namespace App\Policies\Client;

use App\Models\Payment;
use App\Models\CompanyUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, Payment $payment): bool
    {
        return true;
    }
}
