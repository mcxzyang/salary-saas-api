<?php

namespace App\Traits;

use App\Models\CompanyDepartment;
use App\Services\ClientOperateLogService;

trait BootableTrait
{
    protected static function boot()
    {
        parent::boot();
        $moduleName = self::$moduleName;
        $primaryName = self::$primaryName;

        self::created(function ($item) use ($moduleName, $primaryName) {
            static::clientOperateLogService(sprintf('添加%s，名称：%s', $moduleName, $item->$primaryName));
        });

        self::updated(function ($item) use ($moduleName, $primaryName) {
            static::clientOperateLogService(sprintf('更新%s，名称：%s', $moduleName, $item->$primaryName));
        });

        self::deleting(function ($item) use ($moduleName, $primaryName) {
            static::clientOperateLogService(sprintf('删除%s，名称：%s', $moduleName, $item->$primaryName));
        });
    }

    public static function clientOperateLogService($content)
    {
        $moduleName = self::$moduleName;
        app(ClientOperateLogService::class)->save(auth('client')->user(), $moduleName, $content);
    }
}
