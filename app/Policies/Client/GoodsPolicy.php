<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use App\Models\Goods;
use Illuminate\Auth\Access\HandlesAuthorization;

class GoodsPolicy
{
    use HandlesAuthorization;

    public function own(CompanyUser $user, Goods $goods): bool
    {
        return $user->company_id === $goods->company_id;
    }
}
