<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use App\Models\Materiel;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterielPolicy
{
    use HandlesAuthorization;

    public function own(CompanyUser $user, Materiel $materiel): bool
    {
        return $user->company_id === $materiel->company_id;
    }
}
