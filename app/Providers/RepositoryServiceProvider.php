<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {

    public function register()
    {

        $this->app->bind('App\Interfaces\UserInterface', 'App\Repositories\UserRepository');
        $this->app->bind('App\Interfaces\AccountInterface', 'App\Repositories\AccountRepository');
        $this->app->bind('App\Interfaces\ContactInterface', 'App\Repositories\ContactRepository');
        $this->app->bind('App\Interfaces\RoleInterface', 'App\Repositories\RoleRepository');
        $this->app->bind('App\Interfaces\PermissionInterface', 'App\Repositories\PermissionRepository');
        $this->app->bind('App\Interfaces\CategoryInterface', 'App\Repositories\CategoryRepository');
        $this->app->bind('App\Interfaces\SupplierInterface', 'App\Repositories\SupplierRepository');
        $this->app->bind('App\Interfaces\FPInterface', 'App\Repositories\FPRepository');
        $this->app->bind('App\Interfaces\FPDetailInterface', 'App\Repositories\FPDetailRepository');



    }
}
