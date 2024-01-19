<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\CompanyOptionSet;
use Illuminate\Http\Request;

class CompanyOptionSetController extends Controller
{
    public function index()
    {
        $user = auth('client')->user();

        $list = CompanyOptionSet::query()
            ->where('company_id', $user->company_id)
            ->get();
        return $this->success($list);
    }

    public function store(Request $request)
    {
        $user = auth('client')->user();

        $params = $this->validate($request, [
            'list' => 'required|array'
        ]);

        $companyOptionSetIds = [];
        foreach ($params['list'] as $item) {
            if (isset($item['company_option_code']) && isset($item['value'])) {
                $companyOptionSet = CompanyOptionSet::query()->firstOrCreate(array_merge($item, ['company_id' => $user->company_id]));
                $companyOptionSetIds[] = $companyOptionSet->id;
            }
        }
        CompanyOptionSet::query()->where('company_id', $user->company_id)->whereNotIn('id', $companyOptionSetIds)->delete();

        return $this->message('操作成功');
    }
}
