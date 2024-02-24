<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\CreateVendorRequest;
use App\Http\Resources\BaseResource;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = Vendor::filter($request->all())
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(Vendor $vendor)
    {
        // $this->authorize('own', $vendor);

        return $this->success(new BaseResource($vendor));
    }


    public function store(CreateVendorRequest $request, Vendor $vendor)
    {
        $params = $request->all();

        $user = auth('client')->user();

        $vendor->fill(array_merge($params, ['company_id' => $user->company_id]));
        $vendor->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, Vendor $vendor)
    {
        // $this->authorize('own', $vendor);

        $params = $request->all();

        $vendor->fill($params);
        $vendor->save();

        return $this->message('操作成功');
    }

    public function destroy(Vendor $vendor)
    {
        // $this->authorize('own', $vendor);

        $vendor->delete();

        return $this->message('操作成功');
    }
}
