<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Requests\Client\CreatePaymentRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = Payment::filter($request->all())
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(Payment $payment)
    {
        // $this->authorize('own', $payment);

        return $this->success(new BaseResource($payment->load(['collectionAccount', 'order'])));
    }


    public function store(CreatePaymentRequest $request, Payment $payment)
    {
        $params = $request->all();

        $user = auth('client')->user();

        $payment->fill(array_merge($params, ['company_id' => $user->company_id]));
        $payment->save();

        $order = $payment->order;
        if ($order) {
            $received_amount = $order->received_amount + $payment->total;
            $collection_status = 1;
            if ($received_amount >= $order->received_amount) {
                $collection_status = 2;
            }
            $order->collection_status = $collection_status;
            $order->received_amount = $received_amount;
            $order->save();
        }

        return $this->message('操作成功');
    }

    public function update(Request $request, Payment $payment)
    {
        // $this->authorize('own', $payment);

        $params = $request->all();

        $payment->fill($params);
        $payment->save();

        return $this->message('操作成功');
    }

    public function destroy(Payment $payment)
    {
        // $this->authorize('own', $payment);

        $payment->delete();

        return $this->message('操作成功');
    }
}
