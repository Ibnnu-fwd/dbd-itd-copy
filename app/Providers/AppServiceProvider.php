<?php

namespace App\Providers;

use App\Models\DetailKsh;
use App\Models\Ksh;
use App\Models\Larvae;
use App\Models\Sample;
use App\Observers\DetailKshObserver;
use App\Observers\KshObserver;
use App\Observers\LarvaeObserver;
use App\Observers\SampleObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Repositories\Interface\ProvinceInterface::class, \App\Repositories\ProvinceRepository::class);
        $this->app->bind(\App\Repositories\Interface\RegencyInterface::class, \App\Repositories\RegencyRepository::class);
        $this->app->bind(\App\Repositories\Interface\DistrictInterface::class, \App\Repositories\DistrictRepository::class);
        $this->app->bind(\App\Repositories\Interface\TpaTypeInterface::class, \App\Repositories\TpaTypeRepository::class);
        $this->app->bind(\App\Repositories\Interface\FloorTypeInterface::class, \App\Repositories\FloorTypeRepository::class);
        $this->app->bind(\App\Repositories\Interface\EnvironmentTypeInterface::class, \App\Repositories\EnvironmentTypeRepository::class);
        $this->app->bind(\App\Repositories\Interface\VillageInterface::class, \App\Repositories\VillageRepository::class);
        $this->app->bind(\App\Repositories\Interface\LocationTypeInterface::class, \App\Repositories\LocationTypeRepository::class);
        $this->app->bind(\App\Repositories\Interface\SettlementTypeInterface::class, \App\Repositories\SettlementTypeRepository::class);
        $this->app->bind(\App\Repositories\Interface\BuildingTypeInterface::class, \App\Repositories\BuildingTypeRepository::class);
        $this->app->bind(\App\Repositories\Interface\SerotypeInterface::class, \App\Repositories\SerotypeRepository::class);
        $this->app->bind(\App\Repositories\Interface\VirusInterface::class, \App\Repositories\VirusRepository::class);
        $this->app->bind(\App\Repositories\Interface\MorphotypeInterface::class, \App\Repositories\MorphotypeRepository::class);
        $this->app->bind(\App\Repositories\Interface\SampleMethodInterface::class, \App\Repositories\SampleMethodRepository::class);
        $this->app->bind(\App\Repositories\Interface\SampleInterface::class, \App\Repositories\SampleRepository::class);
        $this->app->bind(\App\Repositories\Interface\DetailSampleVirusInterface::class, \App\Repositories\DetailSampleVirusRepository::class);
        $this->app->bind(\App\Repositories\Interface\LarvaeInterface::class, \App\Repositories\LarvaeRepository::class);
        $this->app->bind(\App\Repositories\Interface\KshInterface::class, \App\Repositories\KshRepository::class);
        $this->app->bind(\App\Repositories\Interface\DetailKshInterface::class, \App\Repositories\DetailKshRepository::class);
        $this->app->bind(\App\Repositories\Interface\AbjInterface::class, \App\Repositories\AbjRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sample::observe(SampleObserver::class);
        Larvae::observe(LarvaeObserver::class);
        Ksh::observe(KshObserver::class);
        DetailKsh::observe(DetailKshObserver::class);
    }
}
