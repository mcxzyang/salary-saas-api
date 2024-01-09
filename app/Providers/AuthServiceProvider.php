<?php

namespace App\Providers;

use App\Models\Approve;
use App\Models\Category;
use App\Models\CompanyUser;
use App\Models\Customer;
use App\Models\CustomField;
use App\Models\Defective;
use App\Models\DictItem;
use App\Models\Goods;
use App\Models\Materiel;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stash;
use App\Models\StashTakeStock;
use App\Models\StateFactory;
use App\Models\StateFactoryItemPersonInstance;
use App\Models\Stock;
use App\Models\StockEnter;
use App\Models\StockOut;
use App\Models\WorkingProcess;
use App\Models\WorkingTechnology;
use App\Models\Workorder;
use App\Policies\Client\ApprovePolicy;
use App\Policies\Client\CategoryPolicy;
use App\Policies\Client\CompanyUserPolicy;
use App\Policies\Client\CustomerPolicy;
use App\Policies\Client\CustomFieldPolicy;
use App\Policies\Client\DefectivePolicy;
use App\Policies\Client\DictItemPolicy;
use App\Policies\Client\GoodsPolicy;
use App\Policies\Client\MaterielPolicy;
use App\Policies\Client\OrderPolicy;
use App\Policies\Client\ProductPolicy;
use App\Policies\Client\StashPolicy;
use App\Policies\Client\StashTakeStockPolicy;
use App\Policies\Client\StateFactoryItemPersonInstancePolicy;
use App\Policies\Client\StockEnterPolicy;
use App\Policies\Client\StockOutPolicy;
use App\Policies\Client\StockPolicy;
use App\Policies\Client\WorkingProcessPolicy;
use App\Policies\Client\WorkingTechnologyPolicy;
use App\Policies\Client\WorkorderPolicy;
use App\Policies\StateFactoryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Category::class => CategoryPolicy::class,
        Product::class => ProductPolicy::class,
        CustomField::class => CustomFieldPolicy::class,
        DictItem::class => DictItemPolicy::class,
        Customer::class => CustomerPolicy::class,
        StockEnter::class => StockEnterPolicy::class,
        StockOut::class => StockOutPolicy::class,
        Stash::class => StashPolicy::class,
        StashTakeStock::class => StashTakeStockPolicy::class,
        Stock::class => StockPolicy::class,
        WorkingProcess::class => WorkingProcessPolicy::class,
        WorkingTechnology::class => WorkingTechnologyPolicy::class,
        Defective::class => DefectivePolicy::class,
        Goods::class => GoodsPolicy::class,
        Approve::class => ApprovePolicy::class,
        Workorder::class => WorkorderPolicy::class,
        Materiel::class => MaterielPolicy::class,
        CompanyUser::class => CompanyUserPolicy::class,
        StateFactory::class => StateFactoryPolicy::class,
        Order::class => OrderPolicy::class,
        StateFactoryItemPersonInstance::class => StateFactoryItemPersonInstancePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
