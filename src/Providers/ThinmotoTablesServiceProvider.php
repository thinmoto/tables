<?php

namespace Thinmoto\Tables\Providers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinmoto\Tables\Livewire\Table;

class ThinmotoTablesServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
	    $this->loadViewsFrom(__DIR__.'/../../resources/views', 'ui');

	    $this->publishes([
		    __DIR__.'/../../resources/assets/js' => resource_path('js/vendor/tables'),
		    __DIR__.'/../../resources/assets/scss' => resource_path('scss/vendor/tables'),
	    ], 'assets');

	    Livewire::component('ui::table', Table::class);

	    Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
		    $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
		    return new LengthAwarePaginator(
			    $this->forPage($page, $perPage)->values(),
			    $total ?: $this->count(),
			    $perPage,
			    $page,
			    [
				    'path' => LengthAwarePaginator::resolveCurrentPath(),
				    'pageName' => $pageName,
			    ]
		    );
	    });
    }

    public function register()
    {
//        $this->app->singleton(Package::class, function(){
//            return new Package();
//        });
    }
}
