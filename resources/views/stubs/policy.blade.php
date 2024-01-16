@php
    echo "<?php".PHP_EOL;
@endphp

namespace App\Policies\{{ $moduleName }};

use App\Models\{{ $modelName }};
use App\Models\CompanyUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class {{ $modelName }}Policy
{
    use HandlesAuthorization;


    public function own(CompanyUser $user, {{ $modelName }} ${{ camel($modelName) }}): bool
    {
        return true;
    }
}
