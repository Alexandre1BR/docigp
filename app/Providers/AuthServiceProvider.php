<?php

namespace App\Providers;

use App\Data\Models\CongressmanBudget;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('appUser', function ($user) {
            return true;
        });

        Gate::define('congressman:show', function ($user, $congressman) {
            if (blank($user->department)) {
                return false;
            }

            return $congressman->department->id == $user->department->id;
        });

        Gate::define('congressman-budgets:update:model', function ($user, $congressmanBudget) {
            return blank($user->department_id) ||
                $congressmanBudget->congressman->department_id == $user->department_id;
        });

        Gate::define('entries:update:model', function ($user, $entry) {
            return Gate::allows('congressman-budgets:update:model', $entry->congressmanBudget);
        });

        Gate::define('entry-documents:update:model', function ($user, $entryDocument) {
            return Gate::allows(
                'congressman-budgets:update:model',
                $entryDocument->entry->congressmanBudget
            );
        });

        Gate::define('entry-comments:update:model', function ($user, $entryComment) {
            //Se a pessoa está no mesmo departamento que o criador do comentário
            return $entryComment->user->roles->first()->id == $user->roles->first()->id;
        });

        Gate::define('entry-comments:delete:model', function ($user, $entryComment) {
            //Se a pessoa está no mesmo departamento que o criador do comentário
            return $entryComment->user->roles->first()->id == $user->roles->first()->id;
        });
    }
}
