@php
    echo "<?php".PHP_EOL;
@endphp

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use App\Traits\RecordUserTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class {{ $modelName }} extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;
    use RecordUserTrait;

    protected $fillable = [];

    protected $casts = [];

    public function getAuthGuard()
    {
        return 'client';
    }
}
