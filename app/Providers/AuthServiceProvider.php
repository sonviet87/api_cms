<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        // define all scopes in websites
        $scopes = [
            'admin' => 'full permission',
        ];
        try{
            foreach (Permission::all() as $per) {
                $actions = $per->action;
                if (!empty($actions)) {
                    $actions = json_decode($actions, 1);
                    if (!empty($actions)) {
                        foreach ($actions as $act) {
                            // use scopes to overwrite duplicated data
                            $scopes[$per->controller . ':' . $act] = 1;
                        }
                    }
                }
            }
        }catch(\Exception $e){

        }

        Passport::tokensCan($scopes);
    }
}
