<?php

namespace App\Models;

use App\Traits\BootableTrait;
use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    use FormatDate;
    use BootableTrait;
    use Filterable;

    public static $moduleName = '自定义字段';
    public static $primaryName = 'name';

    public const TYPE_INPUT = 1;
    public const TYPE_TEXTAREA = 2;
    public const TYPE_INPUT_NUMBER = 3;
    public const TYPE_SELECT = 4;
    public const TYPE_SELECT_MULTI = 5;
    public const TYPE_RADIO = 6;
    public const TYPE_CHECKBOX = 7;
    public const TYPE_DATE = 8;
    public const TYPE_DATETIME = 9;
    public const TYPE_DATE_RANGE = 10;

    public static $typeMap = [
        self::TYPE_INPUT => '单行文字输入框',
        self::TYPE_TEXTAREA => '多行文字输入框',
        self::TYPE_INPUT_NUMBER => '数字输入框',
        self::TYPE_SELECT => '下拉框',
        self::TYPE_SELECT_MULTI => '下拉框多选',
        self::TYPE_RADIO => '单选',
        self::TYPE_CHECKBOX => '多选',
        self::TYPE_DATE => '日期选择器',
        self::TYPE_DATETIME => '时期时间选择器',
        self::TYPE_DATE_RANGE => '日期范围选择器'
    ];

    public const MODULE_CUSTOMER = 1;

    public static $moduleMap = [
        self::MODULE_CUSTOMER => '客资模块'
    ];

    protected $fillable = ['id', 'company_id', 'module_id', 'name', 'type', 'options', 'is_required', 'sort', 'status'];
}
