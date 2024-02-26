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
        $this->app->bind('App\Interfaces\ReportInterface', 'App\Repositories\ReportRepository');
        $this->app->bind('App\Interfaces\DebtInterface', 'App\Repositories\DebtRepository');
        $this->app->bind('App\Interfaces\DebtSupplierInterface', 'App\Repositories\DebtSupplierRepository');
        $this->app->bind('App\Interfaces\ReportDebtFPInterface', 'App\Repositories\ReportDebtFPRepository');
        $this->app->bind('App\Interfaces\ReportDebtSupplierInterface', 'App\Repositories\ReportDebtSupplierRepository');
        $this->app->bind('App\Interfaces\WarrantyInterface', 'App\Repositories\WarrantyRepository');
        $this->app->bind('App\Interfaces\KpiMemberGroupsInterface', 'App\Repositories\KpiMemberGroupsRepository');
        $this->app->bind('App\Interfaces\KpiCustomerInterface', 'App\Repositories\KpiCustomerRepository');
        $this->app->bind('App\Interfaces\KpiDebtsInterface', 'App\Repositories\KpiDebtsRepository');
        $this->app->bind('App\Interfaces\KpiSettingsInterface', 'App\Repositories\KpiSettingsRepository');
        $this->app->bind('App\Interfaces\SalaryInterface', 'App\Repositories\SalaryRepository');
        $this->app->bind('App\Interfaces\ChanceInterface', 'App\Repositories\ChanceRepository');


    }
}
