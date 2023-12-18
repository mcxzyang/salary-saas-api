<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use App\Models\StockOut;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockOutPolicy
{
    use HandlesAuthorization;

    public function own(CompanyUser $user, StockOut $stockOut): bool
    {
        return $user->company_id === $stockOut->company_id;
    }

}
