<?php

namespace App\Policies\Client;

use App\Models\FollowUp;
use App\Models\CompanyUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class FollowUpPolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, FollowUp $followUp): bool
    {
        return $user->company_id === $followUp->company_id;
    }
}
