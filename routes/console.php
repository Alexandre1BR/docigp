<?php

use App\Data\Repositories\Users;
use App\Data\Repositories\Budgets;
use App\Data\Repositories\CongressmanBudgets;
use App\Models\CongressmanBudget;
use App\Models\Entry;
use App\Data\Scopes\Published;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Password;
use App\Services\DataSync\Service as DataSyncService;

Artisan::command('docigp:sync:congressmen', function () {
    info('Synchronizing congressmen...');
    login_as_system();
    $this->info('Synchronizing congressmen...');

    app(DataSyncService::class)->congressmen();
})->describe('Sync congressmen data');

Artisan::command('docigp:sync:parties', function () {
    info('Synchronizing parties...');
    login_as_system();
    $this->info('Synchronizing parties...');

    app(DataSyncService::class)->parties();
})->describe('Sync congressmen data');

Artisan::command('docigp:sync:departments', function () {
    info('Creating departments...');
    login_as_system();
    $this->info('Creating departments...');

    app(DataSyncService::class)->departments();
})->describe('Create departments');

Artisan::command('docigp:sync:roles', function () {
    info('Creating roles and abilities...');
    $this->info('Creating roles and abilities...');
    app(DataSyncService::class)->roles();
})->describe('Create roles');

Artisan::command('docigp:budget:generate {baseDate?} {congressmanName?}', function (
    $baseDate = null,
    $congressmanName = null
) {
    info('Generating budgets...');
    login_as_system();
    $this->info('Generating budgets...');

    app(Budgets::class)->generate($baseDate, $congressmanName);
})->describe('Generate budgets {baseDate?} {congressmanName?}');

Artisan::command('docigp:role:assign {role} {email}', function ($role, $email) {
    login_as_system();
    if (!($user = app(Users::class)->findByEmail($email))) {
        return $this->info('E-mail não encontrado.');
    }

    $user->assign($role);

    $this->info("{$user->name} is now '{$role}'");
})->describe('Add role to user {role} {email}');

Artisan::command('docigp:role:retract {role} {email}', function ($role, $email) {
    login_as_system();
    if (!($user = app(Users::class)->findByEmail($email))) {
        return $this->info('E-mail não encontrado.');
    }

    $user->retract($role);

    $this->info("{$user->name} is not '{$role}' anymore");
})->describe('Remove role from user {role} {email}');

Artisan::command('docigp:users:create {email} {name}', function ($email, $name) {
    login_as_system();
    $user = app(Users::class)->firstOrCreate(
        ['email' => $email],
        [
            'name' => $name,
            'email' => $email,
            'username' => $email,
        ]
    );

    $this->info("User {$user->email} created");
})->describe('Create user {email} {name}');

Artisan::command('docigp:users:reset-password {email}', function ($email) {
    login_as_system();

    Password::sendResetLink(['email' => $email]);

    $this->info("Password reset for {$email} was sent");
})->describe('Reset password for user {email}');

Artisan::command('queue:clear {name?}', function ($name = null) {
    Redis::connection()->del($name = $name ?? 'default');

    $this->info("Queue '{$name}' was cleared");
})->describe('Clear queue {name?}');

Artisan::command('docigp:entries:update-transport {id?}', function ($id = null) {
    login_as_system();

    CongressmanBudget::disableGlobalScopes();
    Entry::disableGlobalScopes();

    CongressmanBudget::when($id, function ($query) use ($id) {
        return $query->where('id', $id);
    })->each(function (CongressmanBudget $budget) {
        if (
            $entry = $budget
                ->entries()
                ->orderBy('date', 'asc')
                ->first()
        ) {
            $this->info(sprintf('Updating: %s - %s', $entry->id, $entry->object));

            $entry->save();
        }
    });

    Entry::enableGlobalScopes();
    CongressmanBudget::enableGlobalScopes();
})->describe('Update transport entries touching them');
