<?php

namespace App\Policies\Client;

use App\Models\CollectionAccount;
use App\Models\CompanyUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class CollectionAccountPolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, CollectionAccount $collectionAccount): bool
    {
        return $user->company_id === $collectionAccount->company_id;
    }
}
