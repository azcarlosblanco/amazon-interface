<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Hierarchy values for admin, premum and user
     *
     * @var array
     */
    protected $hierarchy = [
      'superadmin'  => 3,
      'admin' => 2,
      'user'   => 1,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies();

        $gate->define('access', function($user, $role) {
            return $this->checkRoles($user, $role);
        });

        $gate->define('register-country', function($user) {
            return  $this->checkRoles($user, 'superadmin');
        });


    }

        /**
         * Check if current...
         *
         * @param  \App\User  $User
         * @param  string  $requiredRole
         * @return boolean
         */
        public function checkRoles($user, $requiredRole)
        {
            return $this->hierarchy[$user->role] >= $this->hierarchy[$requiredRole];
        }
}
