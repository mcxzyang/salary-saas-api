<?php

namespace App\Traits;

use App\Models\CompanyDepartment;
use App\Services\ClientOperateLogService;

trait BootableTrait
{
    protected static function boot()
    {
        parent::boot();
        $moduleName = self::$moduleName ?? null;
        $primaryName = self::$primaryName ?? null;

        self::created(function ($item) use ($moduleName, $primaryName) {
            static::clientOperateLogService(sprintf('添加%s，名称：%s', $moduleName, $item->$primaryName ?? null));
        });

        self::updated(function ($item) use ($moduleName, $primaryName) {
            static::clientOperateLogService(sprintf('更新%s，名称：%s', $moduleName, $item->$primaryName ?? null));
        });

        self::deleting(function ($item) use ($moduleName, $primaryName) {
            static::clientOperateLogService(sprintf('删除%s，名称：%s', $moduleName, $item->$primaryName ?? null));
        });
    }

    public static function clientOperateLogService($content)
    {
        $moduleName = self::$moduleName;
        app(ClientOperateLogService::class)->save(auth('client')->user(), $moduleName, $content);
    }
}
