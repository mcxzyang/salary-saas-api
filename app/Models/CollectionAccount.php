<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use App\Traits\RecordUserTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class CollectionAccount extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;
    use RecordUserTrait;

    protected $fillable = ['company_id', 'name', 'account_no', 'income_amount', 'out_amount', 'qr_code_image', 'status', 'created_by', 'updated_by'];

    protected $casts = [];

    public function getAuthGuard()
    {
        return 'client';
    }
}
