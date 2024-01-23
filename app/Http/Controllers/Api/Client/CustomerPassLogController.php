<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Requests\Client\CreateCustomerPassLogRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Customer;
use App\Models\CustomerPassLog;
use Illuminate\Http\Request;

class CustomerPassLogController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = CustomerPassLog::filter($request->all())
            ->where('company_id', $user->company_id)
            ->with(['fromUser', 'toUser', 'customer', 'updateUser'])
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(CustomerPassLog $customerPassLog)
    {
        // $this->authorize('own', $customerPassLog);

        return $this->success(new BaseResource($customerPassLog->load(['fromUser', 'toUser'])));
    }


    public function store(CreateCustomerPassLogRequest $request, CustomerPassLog $customerPassLog)
    {
        $params = $request->all();

        $user = auth('client')->user();

        if (isset($params['to_user_id'])) {
            $customer = Customer::query()->where(['company_id' => $user->company_id, 'id' => $params['customer_id']])->first();
            if (!$customer) {
                return $this->failed('客户不存在');
            }
            $customer->in_charge_company_user_id = $params['to_user_id'];
            $customer->save();
        }

        $customerPassLog->fill(array_merge($params, ['company_id' => $user->company_id, 'from_user_id' => $user->id]));
        $customerPassLog->save();

        return $this->message('操作成功');
    }

//    public function update(Request $request, CustomerPassLog $customerPassLog)
//    {
//        // $this->authorize('own', $customerPassLog);
//
//        $params = $request->all();
//
//        $customerPassLog->fill($params);
//        $customerPassLog->save();
//
//        return $this->message('操作成功');
//    }

    public function destroy(CustomerPassLog $customerPassLog)
    {
        // $this->authorize('own', $customerPassLog);

        $customerPassLog->delete();

        return $this->message('操作成功');
    }
}
