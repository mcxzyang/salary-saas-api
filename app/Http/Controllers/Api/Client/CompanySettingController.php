<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\CompanySetting;
use Illuminate\Http\Request;

class CompanySettingController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = CompanySetting::filter($request->all())
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->get();
        return $this->success(BaseResource::collection($list));
    }

    public function show(CompanySetting $companySetting)
    {
        $this->authorize('own', $companySetting);

        return $this->success(new BaseResource($companySetting));
    }

    public function store(Request $request, CompanySetting $companySetting)
    {
        $params = $this->validate($request, [
            'key' => 'required',
            'value' => 'required'
        ]);
        $user = auth('client')->user();

        $checked = CompanySetting::query()->where(['company_id' => $user->company_id, 'key' => $params['key']])->first();
        if ($checked) {
            return $this->failed('该设置已存在');
        }
        CompanySetting::query()->create(array_merge($params, ['company_id' => $user->company_id]));

        return $this->message('操作成功');
    }

    public function update(Request $request, CompanySetting $companySetting)
    {
        $this->authorize('own', $companySetting);

        $params = $request->all();
        $companySetting->fill($params);
        $companySetting->save();

        return $this->message('操作成功');
    }

    public function destroy(CompanySetting $companySetting)
    {
        $this->authorize('own', $companySetting);

        $companySetting->delete();

        return $this->message('操作成功');
    }
}
