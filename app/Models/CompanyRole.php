<?php

namespace App\Models;

use App\Traits\BootableTrait;
use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class CompanyRole extends Model
{
    use FormatDate;
    use Filterable;
    use BootableTrait;

    public static $moduleName = '角色';
    public static $primaryName = 'name';

    protected $fillable = ['company_id', 'name', 'status'];

    public function companyMenus()
    {
        return $this->belongsToMany(CompanyMenu::class, 'company_role_menus', 'company_role_id', 'company_menu_id');
    }
}
