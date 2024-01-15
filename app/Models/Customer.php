<?php

namespace App\Models;

use App\Traits\BootableTrait;
use App\Traits\FormatDate;
use App\Traits\LogsActivityTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use FormatDate;
    use Filterable;
    use LogsActivityTrait;

//    public static $moduleName = '客户';
//    public static $primaryName = 'name';

    protected $fillable = [
        'id',
        'company_id',
        'in_charge_company_user_id',
        'type',
        'name',
        'sex',
        'city',
        'phone',
        'customer_status_id',
        'customer_type_id',
        'level_id',
        'source_id',
        'ripeness_id',
        'industry_id',
        'description',
        'link_man',
        'status',
        'get_customer_time'
    ];

    protected $casts = [
        'get_customer_time' => 'datetime'
    ];

    public function inChargeCompanyUser()
    {
        return $this->belongsTo(CompanyUser::class, 'in_charge_company_user_id');
    }

    public function customerStatus()
    {
        return $this->belongsTo(DictItem::class, 'customer_status_id');
    }

    public function type()
    {
        return $this->belongsTo(DictItem::class, 'type_id');
    }

    public function level()
    {
        return $this->belongsTo(DictItem::class, 'level_id');
    }

    public function source()
    {
        return $this->belongsTo(DictItem::class, 'source_id');
    }

    public function ripeness()
    {
        return $this->belongsTo(DictItem::class, 'ripeness_id');
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class, 'industry_id');
    }

    public function customFieldModuleContents()
    {
        $customModule = CustomModule::query()->where('code', CustomModule::CODE_CUSTOMER)->first();
        return $this->hasMany(CustomFieldModuleContent::class, 'model_id')
            ->with(['customField'])
            ->where('custom_module_id', $customModule->id);
    }
}
