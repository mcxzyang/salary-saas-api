<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Materiel;
use Illuminate\Http\Request;

class MaterielController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = Materiel::filter($request->all())
            ->where('is_deleted', 0)
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(Materiel $materiel)
    {
        $this->authorize('own', $materiel);

        return $this->success(new BaseResource($materiel->load(['goods', 'stash'])));
    }

    public function store(Request $request, Materiel $materiel)
    {
        $this->validate($request, [
            'name' => 'required',
            'spec' => 'required',
        ]);

        $user = auth('client')->user();

        $params = $request->all();

        $materiel->fill(array_merge($params, ['company_id' => $user->company_id]));
        $materiel->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, Materiel $materiel)
    {
        $this->authorize('own', $materiel);

        $params = $request->all();

        $materiel->fill($params);
        $materiel->save();

        return $this->message('操作成功');
    }

    public function destroy(Materiel $materiel)
    {
        $this->authorize('own', $materiel);

        $materiel->is_deleted = 1;
        $materiel->save();

        return $this->message('操作成功');
    }
}
