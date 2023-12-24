<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use App\Models\Stock;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockPolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, Stock $stock): bool
    {
        return $user->company_id === $stock->company_id;
    }

}
