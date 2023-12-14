<?php

namespace App\Policies\Client;

use App\Models\CompanyUser;
use App\Models\CustomField;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomFieldPolicy
{
    use HandlesAuthorization;

    public function own(CompanyUser $user, CustomField $customField): bool
    {
        return $user->company_id === $customField->company_id;
    }
}
