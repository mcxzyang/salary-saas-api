<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use App\Models\StashTakeStock;
use Illuminate\Auth\Access\HandlesAuthorization;

class StashTakeStockPolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, StashTakeStock $stashTakeStock): bool
    {
        return $user->company_id === $stashTakeStock->company_id;
    }

}
