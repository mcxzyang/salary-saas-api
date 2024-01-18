<?php

namespace App\Policies\Client;

use App\Models\GoodsCategory;
use App\Models\CompanyUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class GoodsCategoryPolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, GoodsCategory $goodsCategory): bool
    {
        return $user->company_id === $goodsCategory->id;
    }
}
