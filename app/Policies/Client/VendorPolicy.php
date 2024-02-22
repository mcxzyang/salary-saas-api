<?php

namespace App\Policies\Client;

use App\Models\Vendor;
use App\Models\CompanyUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorPolicy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, Vendor $vendor): bool
    {
        return true;
    }
}
