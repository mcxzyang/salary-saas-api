<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use App\Models\DictItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class DictItemPolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, DictItem $dictItem): bool
    {
        return $user->company_id === $dictItem->company_id;
    }
}
