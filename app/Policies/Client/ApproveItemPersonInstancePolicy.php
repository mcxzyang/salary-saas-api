<?php

namespace App\Policies\Client;

use App\Models\ApproveItemPersonInstance;
use App\Models\CompanyUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApproveItemPersonInstancePolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, ApproveItemPersonInstance $approveItemPersonInstance): bool
    {
        return $user->company_id === $approveItemPersonInstance->company_id;
    }
}
