<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ClientOperateLogController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = Activity::query()->where('company_id', $user->company_id)
            ->with(['causer'])
            ->orderBy('id', 'desc')
            ->paginateOrGet();
//        $list = ClientOperateLog::query()
//            ->with(['companyUser'])
//            ->where('company_id', $user->company_id)
//            ->orderBy('id', 'desc')
//            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }
}
