<?php

namespace App\Models;

use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class CompanyMenu extends Model
{
    use FormatDate;
    use Filterable;

    protected $fillable = [
        'path', 'name', 'title', 'icon', 'view_path', 'type', 'parent_id', 'sort',
        'hidden', 'activeMenu', 'dynamicNewTab', 'noKeepAlive', 'type', 'permission', 'status', 'noClosable'
    ];

    protected $casts = [
        'noKeepAlive' => 'boolean',
        'hidden' => 'boolean',
        'dynamicNewTab' => 'boolean',
        'noClosable' => 'boolean'
    ];

    public function child()
    {
        return $this->hasMany(CompanyMenu::class, 'parent_id')->orderBy('sort');
    }

    public function parts()
    {
        return $this->hasMany(get_class($this), 'parent_id', 'id');
    }

    public function children()
    {
        return $this->parts()->with('children');
    }

    public function roles()
    {
        return $this->belongsToMany(CompanyRole::class, 'company_menu_roles', 'company_menu_id', 'company_role_id');
    }

    public function parent()
    {
        return $this->belongsTo(CompanyMenu::class, 'parent_id');
    }

//    public function buttons()
//    {
//        return $this->hasMany(Button::class);
//    }
}
