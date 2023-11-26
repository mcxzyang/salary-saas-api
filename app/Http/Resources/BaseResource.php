<?php

namespace App\Http\Resources;

use App\Http\Resources\Collections\BaseResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    public static $wrap = 'list';
    /**
     * @var array
     */
    protected $withoutFields = [];

    public function toArray($request)
    {
        return $this->filterFields(parent::toArray($request));
    }

    public function hide(array $fields)
    {
        $this->withoutFields = $fields;
        return $this;
    }

    /**
     * Remove the filtered keys.
     *
     * @param $array
     *
     * @return array
     */
    protected function filterFields($array)
    {
        return collect($array)->forget($this->withoutFields)->toArray();
    }

    public static function collection($resource)
    {
        return tap(new BaseResourceCollection($resource, static::class), function ($collection) {
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([]))->preserveKeys === true;
            }
        });
    }
}
