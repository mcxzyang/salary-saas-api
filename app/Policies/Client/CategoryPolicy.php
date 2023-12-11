<?php

namespace App\Policies\Client;

use App\Models\Category;
use App\Models\CompanyUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function own(CompanyUser $user, Category $category): bool
    {
        return $user->company_id === $category->company_id;
    }
}
