<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Approve;
use App\Models\ApproveItem;
use App\Models\ApproveItemPerson;
use App\Models\ApproveScope;
use Illuminate\Http\Request;

class ApproveController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = Approve::filter($request->all())
            ->where('is_deleted', 0)
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(Approve $approve)
    {
        $this->authorize('own', $approve);

        return $this->success(new BaseResource($approve->load(['approveScopes.department', 'approveScopes.companyUser', 'approveItems.approveItemPersons.companyUser'])));
    }

    public function store(Request $request, Approve $approve)
    {
        $this->validate($request, [
            'type' => 'required',
            'name' => 'required',
            'scope' => 'required|in:1,2,3'
        ]);

        $params = $request->all();

        $user = auth('client')->user();

        $approve->fill(array_merge($params, ['company_id' => $user->company_id]));
        $approve->save();

        // scope
        if (isset($params['scope_list']) && count($params['scope_list'])) {
            foreach ($params['scope_list'] as $relationId) {
                $saveParams = [
                    'approve_id' => $approve->id,
                    'scope' => $approve->scope
                ];
                if ($approve->scope === 2) {
                    $saveParams['department_id'] = $relationId;
                }
                if ($approve->scope === 3) {
                    $saveParams['company_user_id'] = $relationId;
                }
                ApproveScope::query()->create($saveParams);
            }
        }

        // approve item
        if (isset($params['approve_items']) && count($params['approve_items'])) {
            foreach ($params['approve_items'] as $approveItemParams) {
                $approveItem = ApproveItem::query()->create([
                    'approve_id' => $approve->id,
                    'name' => $approveItemParams['name'] ?? null,
                    'is_allow_edit' => $approveItemParams['is_allow_edit'] ?? null,
                    'condition_type' => $approveItemParams['condition_type'] ?? null,
                    'sort' => $approveItemParams['sort'] ?? null,
                    'status' => 1
                ]);

                // persons
                if (isset($approveItemParams['persons']) && count($approveItemParams['persons'])) {
                    foreach ($approveItemParams['persons'] as $personId) {
                        ApproveItemPerson::query()->create([
                            'approve_item_id' => $approveItem->id,
                            'company_user_id' => $personId
                        ]);
                    }
                }
            }
        }

        return $this->message('操作成功');
    }

    public function update(Request $request, Approve $approve)
    {
        $this->authorize('own', $approve);

        $params = $request->all();

        $approve->fill($params);
        $approve->save();

        // scope
        $approveScopeIds = [];
        if (isset($params['scope_list']) && count($params['scope_list'])) {
            foreach ($params['scope_list'] as $relationId) {
                $saveParams = [
                    'approve_id' => $approve->id,
                    'scope' => $approve->scope
                ];
                if ($approve->scope === 2) {
                    $saveParams['department_id'] = $relationId;
                }
                if ($approve->scope === 3) {
                    $saveParams['company_user_id'] = $relationId;
                }
                $approveScope = ApproveScope::query()->firstOrCreate($saveParams);

                $approveScopeIds[] = $approveScope->id;
            }
        }
        ApproveScope::query()->where('approve_id', $approve->id)->whereNotIn('id', $approveScopeIds)->delete();

        // approve item
        $approveItemIds = [];
        if (isset($params['approve_items']) && count($params['approve_items'])) {
            foreach ($params['approve_items'] as $approveItemParams) {
                $approveItem = new ApproveItem([
                    'name' => $approveItemParams['name'] ?? null,
                    'is_allow_edit' => $approveItemParams['is_allow_edit'] ?? null,
                    'condition_type' => $approveItemParams['condition_type'] ?? null,
                    'sort' => $approveItemParams['sort'] ?? null,
                    'status' => 1
                ]);
                if (isset($approveItemParams['id']) && $approveItemParams['id']) {
                    $approveItem = ApproveItem::query()->where('id', $approveItemParams['id'])->first();
                }
                $approveItem->fill($approveItemParams);
                $approveItem->save();

                $approveItemIds[] = $approveItem->id;

                // persons
                $approveItemPersonIds = [];
                if (isset($approveItemParams['persons']) && count($approveItemParams['persons'])) {
                    foreach ($approveItemParams['persons'] as $personId) {
                        $approveItemPerson = ApproveItemPerson::query()->firstOrCreate([
                            'approve_item_id' => $approveItem->id,
                            'company_user_id' => $personId
                        ]);
                        $approveItemPersonIds[] = $approveItemPerson->id;
                    }
                }
                ApproveItemPerson::query()->where('approve_item_id', $approveItem->id)->whereNotIn('id', $approveItemPersonIds)->delete();
            }
            ApproveItem::query()->where('approve_id', $approve->id)->whereNotIn('id', $approveItemIds)->delete();
        }
        return $this->message('操作成功');
    }

    public function destroy(Approve $approve)
    {
        $approve->is_deleted = 1;
        $approve->save();

        return $this->message('操作成功');
    }

    public function typeList()
    {
        $list = Approve::$typeMap;
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
