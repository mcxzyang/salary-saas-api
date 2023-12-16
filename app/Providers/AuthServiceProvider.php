<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\CustomField;
use App\Models\DictItem;
use App\Models\Product;
use App\Policies\Client\CategoryPolicy;
use App\Policies\Client\CustomerPolicy;
use App\Policies\Client\CustomFieldPolicy;
use App\Policies\Client\DictItemPolicy;
use App\Policies\Client\ProductPolicy;
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
