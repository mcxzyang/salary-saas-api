<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\StateFactory;
use App\Models\StateFactoryItem;
use App\Models\StateFactoryItemPerson;
use Illuminate\Http\Request;

class StateFactoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('client')->user();

        $list = StateFactory::filter($request->all())
            ->where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show(StateFactory $stateFactory)
    {
        $this->authorize('own', $stateFactory);

        return $this->success(new BaseResource($stateFactory->load(['stateFactoryItems.stateFactoryItemPersons.companyUser'])));
    }

    public function store(Request $request, StateFactory $stateFactory)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $params = $request->all();

        $user = auth('client')->user();

        \DB::transaction(function () use ($stateFactory, $params, $user, $request) {
            $stateFactory->fill(array_merge($params, ['company_id' => $user->company_id]));
            $stateFactory->save();

            if (isset($params['state_factory_items']) && count($params['state_factory_items'])) {
                $this->validate($request, [
                    'state_factory_items.*.name' => 'required',
                    'state_factory_items.*.sort' => 'required',
                ]);
                foreach ($params['state_factory_items'] as $stateFactoryItemParams) {
                    $stateFactoryItem = StateFactoryItem::query()->create(array_merge($stateFactoryItemParams, ['state_factory_id' => $stateFactory->id]));

                    if (isset($stateFactoryItemParams['persons']) && count($stateFactoryItemParams['persons'])) {
                        foreach ($stateFactoryItemParams['persons'] as $personId) {
                            StateFactoryItemPerson::query()->create([
                                'state_factory_item_id' => $stateFactoryItem->id,
                                'company_user_id' => $personId
                            ]);
                        }
                    }
                }
            }
        });

        return $this->message('操作成功');
    }

    public function update(Request $request, StateFactory $stateFactory)
    {
        $this->authorize('own', $stateFactory);

        $params = $request->all();

        \DB::transaction(function () use ($stateFactory, $params) {
            $stateFactory->fill($params);
            $stateFactory->save();

            $stateFactoryItemIds = [];
            if (isset($params['state_factory_items']) && count($params['state_factory_items'])) {
                foreach ($params['state_factory_items'] as $stateFactoryItemParams) {
                    $stateFactoryItem = new StateFactoryItem(['state_factory_id' => $stateFactory->id]);
                    if (isset($stateFactoryItemParams['id']) && $stateFactoryItemParams['id']) {
                        $stateFactoryItem = StateFactoryItem::query()->where('id', $stateFactoryItemParams['id'])->first();
                    }
                    $stateFactoryItem->fill($stateFactoryItemParams);
                    $stateFactoryItem->save();
                    $stateFactoryItemIds[] = $stateFactoryItem->id;

                    $stateFactoryItemPersonIds = [];
                    if (isset($stateFactoryItemParams['persons']) && count($stateFactoryItemParams['persons'])) {
                        foreach ($stateFactoryItemParams['persons'] as $personId) {
                            $stateFactoryItemPerson = StateFactoryItemPerson::query()->firstOrCreate([
                                'state_factory_item_id' => $stateFactoryItem->id,
                                'company_user_id' => $personId
                            ]);
                            $stateFactoryItemPersonIds[] = $stateFactoryItemPerson->id;
                        }
                    }
                    StateFactoryItemPerson::query()->where('state_factory_item_id', $stateFactoryItem->id)->whereNotIn('id', $stateFactoryItemPersonIds)->delete();
                }
            }
            StateFactoryItem::query()->where('state_factory_id', $stateFactory->id)->whereNotIn('id', $stateFactoryItemIds)->delete();
        });

        return $this->message('操作成功');
    }

    public function destroy(StateFactory $stateFactory)
    {
        $this->authorize('own', $stateFactory);

        \DB::transaction(function () use ($stateFactory) {
            $stateFactoryItems = StateFactoryItem::query()->where('state_factory_id', $stateFactory)->get();
            foreach ($stateFactoryItems as $stateFactoryItem) {
                StateFactoryItemPerson::query()->where('state_factory_item_id', $stateFactoryItem->id)->delete();
                $stateFactoryItem->delete();
            }
            $stateFactory->delete();
        });

        return $this->message('操作成功');
    }
}
