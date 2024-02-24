<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminRoleResource;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $list = Client::filter($request->all())
            ->with(['company'])
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(AdminRoleResource::collection($list));
    }

    public function store(Request $request, Client $client)
    {
        $this->validate($request, [
            'company_id' => 'required',
            'type' => 'required'
        ]);

        $params = $request->all();

        $client->fill($params);
        $client->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, Client $client)
    {
        $params = $request->all();

        $client->fill($params);
        $client->save();

        return $this->message('操作成功');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return $this->message('操作成功');
    }

    public function typeList()
    {
        $list = Client::$typeMap;
        $arr = [];
        foreach ($list as $key => $value) {
            $arr[] = [
                'id' => $key,
                'name' => $value
            ];
        }

        return $this->success($arr);
    }
}
