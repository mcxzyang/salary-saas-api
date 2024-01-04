<?php

namespace App\Traits;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

trait LogsActivityTrait
{
    use LogsActivity;

    protected $logName = '';
    protected function getDescriptionForEvent(string $eventName): string
    {
        $description = '';
        switch ($eventName) {
            case 'created':
                $description = '用户' . auth('client')->user()->name . '添加了' . $this->table.'表id为' . $this->id . '的数据';
                break;
            case 'updated':
                $description = '用户' . auth('client')->user()->name . '修改了' . $this->table.'表id为' . $this->id . '的数据';
                break;
            case 'deleted':
                $description = '用户' . auth('client')->user()->name . '删除了' . $this->table.'表id为' . $this->id . '的数据';
                break;
        }
        return $description;
    }

    protected function getLogName(): string
    {
        return $this->logName ?: strtolower(class_basename($this)); // 小写类名作为日志名方便分类
    }

    protected function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // 记录所有字段的更改
                ->logExcept(['updated_at'])
            ->dontLogIfAttributesChangedOnly(['updated_at']) // 当只有updated_at 字段更改时不记录
            ->logOnlyDirty() // 只有实际发生更改的字段记录
            ->dontSubmitEmptyLogs() // 不记录空日志
            ->useLogName($this->getLogName()) // 设置日志名
            ->setDescriptionForEvent(function (string $eventName) {
                return $this->getDescriptionForEvent($eventName);
            });
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($user = auth('client')->user()) {
            $activity->company_id = $user->company_id;
        }
    }
}
