<?php

namespace App\Providers;

use App\Http\Resources\Collections\BaseResourceCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('paginateOrGet', function () {
            $paging = request('paging', 1);
            return $paging ? $this->paginate(request('size', config('app.size'))) : $this->get();
        });

//        BaseResourceCollection::withoutWrapping();
    }
}
