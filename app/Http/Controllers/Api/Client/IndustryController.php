<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Industry;
use Illuminate\Http\Request;

class IndustryController extends Controller
{
    public function index(Request $request)
    {
        $parentCode = $request->input('parent_code', '0');
        $list = Industry::query()->where('parent_code', $parentCode)->get();
        return $this->success(BaseResource::collection($list));
    }
}
