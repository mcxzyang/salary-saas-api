<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\CustomModule;
use App\Models\Defective;
use App\Services\CustomFieldService;
use Illuminate\Http\Request;

class DefectiveController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = Defective::filter($request->all())
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(Defective $defective)
    {
        $this->authorize('own', $defective);

        return $this->success(new BaseResource($defective));
    }

    public function store(Request $request, Defective $defective)
    {
        $this->validate($request, [
            'name' => 'required'
        ], [
            'name.required' => '请填写名称'
        ]);

        $user = auth('client')->user();

        $params = $request->all();

        $defective->fill(array_merge($params, ['company_id' => $user->company_id, 'status' => 1]));
        $defective->save();

        app(CustomFieldService::class)->createOrUpdate($params['$customFields'] ?? [], CustomModule::CODE_DEFECTIVE, $defective->id);

        return $this->message('操作成功');
    }

    public function update(Request $request, Defective $defective)
    {
        $this->authorize('own', $defective);

        $params = $request->all();

        $defective->fill($params);
        $defective->save();

        app(CustomFieldService::class)->createOrUpdate($params['$customFields'] ?? [], CustomModule::CODE_DEFECTIVE, $defective->id);

        return $this->message('操作成功');
    }

    public function destroy(Defective $defective)
    {
        $defective->delete();

        return $this->message('操作成功');
    }
}
