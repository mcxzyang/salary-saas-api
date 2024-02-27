<?php

namespace App\Http\Controllers\Api\Client;

use App\Events\ApproveInstanceFinishedEvent;
use App\Http\Controllers\Controller;
use App\Models\ApproveInstance;
use App\Models\Order;
use App\Models\ProductSku;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

//use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{
    public function index1()
    {
        $productSku = ProductSku::query()->where('id', 1)->first();
        if ($productSku->stock <= 0) {
            return $this->failed('已卖完');
        }
        \Log::info($productSku->stock);
        $productSku->decrement('stock');

        return $this->message('秒杀成功');
    }

    public function index()
    {
        try {
            return Cache::lock('productSku:1', 10)->block(5, function () {
//                $productSku = ProductSku::query()->where('id', 1)->first();
                $stock = Redis::get('productSku');
                if ($stock <= 0) {
                    \Log::info('卖完了');
                    return $this->failed('已卖完');
                }
                \Log::info($stock);
                Redis::decr('productSku');

                return $this->message('秒杀成功');
            });
        } catch (LockTimeoutException $exception) {
            \Log::info('抢完了');
            return $this->failed('抢完了');
        } catch (\Exception $exception) {
            \Log::info('发生错误');
            return $this->failed('发生错误');
        }
    }

    public function setStock()
    {
        Redis::set('productSku', 100);
    }

    public function decr()
    {
        Redis::decr('productSku');
    }

    public function testJob()
    {
        $model = Order::query()->where('id', 13)->first();
        $approveInstance = ApproveInstance::query()->where('id', 7)->first();

        event(new ApproveInstanceFinishedEvent($model, $approveInstance));
    }
}
