<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\WorkingTechnology;
use App\Models\WorkingTechnologyItem;
use Illuminate\Http\Request;

class WorkingTechnologyController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = WorkingTechnology::filter($request->all())
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(WorkingTechnology $workingTechnology)
    {
        $this->authorize('own', $workingTechnology);

        return $this->success(new BaseResource($workingTechnology->load(['workingTechnologyItems', 'createdUser'])));
    }

    public function store(Request $request, WorkingTechnology $workingTechnology)
    {
        $params = $this->validate($request, [
            'name' => 'required'
        ], [
            'name.required' => '请填写工艺名称'
        ]);

        $user = auth('client')->user();

        $workingTechnology->fill(array_merge($params, ['company_id' => $user->company_id, 'created_by' => $user->id, 'status' => 1]));
        $workingTechnology->save();

        $items = $request->input('working_technology_items', []);
        if ($items && count($items)) {
            foreach ($items as $item) {
                WorkingTechnologyItem::query()->create([
                    'working_technology_id' => $workingTechnology->id,
                    'working_process_id' => $item['working_process_id'],
                    'sort' => $item['sort'] ?? null
                ]);
            }
        }

        return $this->message('操作成功');
    }

    public function update(Request $request, WorkingTechnology $workingTechnology)
    {
        $this->authorize('own', $workingTechnology);

        $params = $request->all();

        $workingTechnology->fill($params);
        $workingTechnology->save();

        $items = $request->input('working_technology_items', []);
        $itemIds = [];
        if ($items && count($items)) {
            foreach ($items as $item) {
                $workingTechnologyItem = WorkingTechnologyItem::query()->where('id', $item['id'])->first();
                if (!$workingTechnologyItem) {
                    $workingTechnologyItem = new WorkingTechnologyItem([
                        'working_technology_id' => $workingTechnology->id
                    ]);
                }
                $workingTechnologyItem->fill($item);
                $workingTechnologyItem->save();
                $itemIds[] = $workingTechnologyItem->id;
            }
        }

        WorkingTechnologyItem::query()->where('working_technology_id', $workingTechnology->id)->whereNotIn('id', $itemIds)->delete();

        return $this->message('操作成功');
    }

    public function destroy(WorkingTechnology $workingTechnology)
    {
        $this->authorize('own', $workingTechnology);

        $workingTechnology->is_deleted = 0;
        $workingTechnology->save();

        return $this->message('操作成功');
    }
}
