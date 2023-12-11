<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, Product $product): bool
    {
        return $user->company_id === $product->company_id;
    }
}
