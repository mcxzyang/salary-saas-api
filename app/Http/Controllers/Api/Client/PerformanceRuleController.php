<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\PerformanceRule;
use App\Models\PerformanceRuleItem;
use Illuminate\Http\Request;

class PerformanceRuleController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = PerformanceRule::filter($request->all())
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function store(Request $request, PerformanceRule $performanceRule)
    {
        $this->validate($request, [
            'name' => 'required',
            'type' => 'required',
            'salary_type' => 'required',
            'price' => 'required'
        ]);

        $user = auth('client')->user();

        $params = $request->all();
        $performanceRule->fill(array_merge($params, ['company_id' => $user->company_id]));
        $performanceRule->save();

        if (isset($params['performance_rule_items']) && count($params['performance_rule_items'])) {
            foreach ($params['performance_rule_items'] as $performanceRuleItemParam) {
                PerformanceRuleItem::query()->create(array_merge($performanceRuleItemParam, ['performance_rule_id' => $performanceRule->id]));
            }
        }

        return $this->message('操作成功');
    }

    public function show(PerformanceRule $performanceRule)
    {
        $this->authorize('own', $performanceRule);

        return $this->success(new BaseResource($performanceRule->load(['performanceRuleItems'])));
    }

    public function update(Request $request, PerformanceRule $performanceRule)
    {
        $this->authorize('own', $performanceRule);

        $params = $request->all();

        $performanceRule->fill($params);
        $performanceRule->save();

        $performanceRuleItemIds = [];
        if (isset($params['performance_rule_items']) && count($params['performance_rule_items'])) {
            foreach ($params['performance_rule_items'] as $performanceRuleItemParam) {
                $performanceRuleItem = new PerformanceRuleItem(['performance_rule_id' => $performanceRule->id]);
                if (isset($performanceRuleItemParam['id']) && $performanceRuleItemParam['id']) {
                    $performanceRuleItem = PerformanceRuleItem::query()->where('id', $performanceRuleItemParam['id'])->first();
                }
                $performanceRuleItem->fill($performanceRuleItemParam);
                $performanceRuleItem->save();

                $performanceRuleItemIds[] = $performanceRuleItem->id;
            }
        }
        PerformanceRuleItem::query()->where('performance_rule_id', $performanceRule->id)->whereNotIn('id', $performanceRuleItemIds)->delete();

        return $this->message('操作成功');
    }

    public function destroy(PerformanceRule $performanceRule)
    {
        $performanceRule->performanceRuleItems()->delete();
        $performanceRule->delete();

        return $this->message('操作成功');
    }
}
