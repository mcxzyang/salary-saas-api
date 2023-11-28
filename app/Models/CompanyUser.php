<?php

namespace App\Models;

use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class CompanyUser extends Authenticatable implements JWTSubject
{
    use FormatDate;
    use Filterable;

    protected $fillable = ['company_id', 'name', 'username', 'password', 'phone', 'is_super_admin', 'status'];

    protected $hidden = [
        'password'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected static function booted()
    {
        static::saving(function (CompanyUser $companyUser) {
            if (\Hash::needsRehash($companyUser->password)) {
                $companyUser->password = \bcrypt($companyUser->password);
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function roles()
    {
        return $this->belongsToMany(CompanyRole::class, 'company_user_roles', 'company_user_id', 'company_role_id');
    }

    public function departments()
    {
        return $this->belongsToMany(CompanyDepartment::class, 'company_user_departments', 'company_user_id', 'company_department_id');
    }
}
