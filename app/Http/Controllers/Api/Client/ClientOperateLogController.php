<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\ClientOperateLog;
use Illuminate\Http\Request;

class ClientOperateLogController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = ClientOperateLog::query()
            ->with(['companyUser'])
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }
}
