<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use App\Models\StockEnter;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockEnterPolicy
{
    use HandlesAuthorization;

    public function own(CompanyUser $user, StockEnter $stockEnter): bool
    {
        return $user->company_id === $stockEnter->company_id;
    }

}
