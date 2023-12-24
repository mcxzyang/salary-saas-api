<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use App\Models\Stash;
use Illuminate\Auth\Access\HandlesAuthorization;

class StashPolicy
{
    use HandlesAuthorization;

    public function own(CompanyUser $user, Stash $stash): bool
    {
        return $user->company_id === $stash->company_id;
    }
}
