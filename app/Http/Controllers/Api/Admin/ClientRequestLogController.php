<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\ClientRequestLog;
use Illuminate\Http\Request;

class ClientRequestLogController extends Controller
{
    public function index(Request $request)
    {
        $list = ClientRequestLog::filter($request->all())
            ->select(['request_url', 'request_method', 'status_code', 'location', 'client_ip', 'browser', 'id', 'created_at', 'duration'])
            ->orderBy('id', 'desc')->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(ClientRequestLog $clientRequestLog)
    {
        return $this->success(new BaseResource($clientRequestLog));
    }
}
