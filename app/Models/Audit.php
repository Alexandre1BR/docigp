<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;

class Audit extends Model
{
    protected $controlCreatedBy = false;

    protected $controlUpdatedBy = false;

    protected $with = ['user'];

    protected $dates = ['created_at', 'updated_at'];

    protected $appends = ['formatted_created_at', 'activity', 'route_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('d/m/Y H:i:s') : null;
    }

    public function getRouteNameAttribute()
    {
        return Str::startsWith($this->url, 'console')
            ? 'Rotina do sistema'
            : ($route = app('router')
                ->getRoutes()
                ->match(app('request')->create($this->url, 'POST'))
                ->getName());
    }

    public function getActivityAttribute()
    {
        $url = $this->url;

        if (Str::startsWith($url, 'console')) {
            return 'Rotina do sistema';
        }

        $route = $this->route_name;
        $activity = '';

        if (!$route) {
            $activity = 'Login';
        }

        if (Str::startsWith($route, 'logout')) {
            $activity = 'Logout';
        }

        if (Str::endsWith($route, 'congressman-legislatures.edit-legislature')) {
            $activity = 'Modificação das datas de deputado em legislaturas';
        }

        if (Str::endsWith($route, 'congressman-legislatures.include-in-legislature')) {
            $activity = 'Modificação das datas de deputado em legislaturas';
        }

        if (Str::endsWith($url, 'providers.update-form')) {
            $activity = 'Modificação de período de bloqueio';
        }

        if (Str::endsWith($url, 'admin/congressmen')) {
            $activity = 'Modificação de deputado';
        }

        $found = false;
        collect([
            ['suffix' => '.analyse', 'name' => 'Análise'],
            ['suffix' => '.unanalyse', 'name' => 'Retirada de análise'],
            ['suffix' => '.verify', 'name' => 'Verificação'],
            ['suffix' => '.unverify', 'name' => 'Retirada de verificação'],
            ['suffix' => '.publish', 'name' => 'Publicação'],
            ['suffix' => '.unpublish', 'name' => 'Retirada de publicação'],
            ['suffix' => '.close', 'name' => 'Fechamento'],
            ['suffix' => '.reopen', 'name' => 'Abertura'],
            ['suffix' => '.update', 'name' => 'Modificação'],
            ['suffix' => '.store', 'name' => 'Criação'],
            ['suffix' => '.delete', 'name' => 'Deleção'],
            ['suffix' => '.deposit', 'name' => 'Depósito'],
        ])->each(function ($item) use (&$activity, $route, &$found) {
            if (!$found) {
                if (Str::endsWith($route, $item['suffix'])) {
                    $activity .= $item['name'];
                    $found = true;
                }
            }
        });

        $found = false;
        collect([
            ['prefix' => 'congressmen.budgets.entries.', 'name' => 'Lançamento'],
            ['prefix' => 'entries.', 'name' => 'Lançamento'],
            ['prefix' => 'congressmen.budgets.entries-comments.', 'name' => 'Comentário'],
            ['prefix' => 'congressmen.budgets.entries-documents.', 'name' => 'Documento'],
            ['prefix' => 'providers.', 'name' => 'Fornecedor'],
            ['prefix' => 'congressmen.budgets.', 'name' => 'Orçamento Mensal'],
            //            ['prefix' => 'congressmen.', 'name' => 'Deputado'],
            ['prefix' => 'password.', 'name' => 'Senha'],
            ['prefix' => 'users.', 'name' => 'Usuário'],
            ['prefix' => 'parties.', 'name' => 'Partido'],
            ['prefix' => 'entry-types.', 'name' => 'Tipo de Lançamento'],
            ['prefix' => 'cost-centers.', 'name' => 'Centro de Custo'],
            ['prefix' => 'legislatures.', 'name' => 'Legislatura'],
        ])->each(function ($item) use (&$activity, $route, &$found) {
            if (!$found) {
                if (Str::startsWith($route, $item['prefix'])) {
                    $activity .= ' de ' . $item['name'];
                    $found = true;
                }
            }
        });

        if (!$activity) {
            info('Não há activity registrada para o audits.id = ' . $this->id);
        }

        return $activity;
    }

    public function getEntityAttribute()
    {
        $type = explode('\\', $this->auditable_type);
        return end($type);
    }

    public function auditable()
    {
        return $this->morphTo();
    }

    public function getLastModel($relation, $modelId)
    {
        $classArray = [
            'Entry' => Entry::class,
            'EntryComment' => EntryComment::class,
            'EntryDocument' => EntryDocument::class,
            'CostCenter' => CostCenter::class,
            'EntryType' => EntryType::class,
            'ProviderBlockPeriod' => ProviderBlockPeriod::class,
            'Provider' => Provider::class,
            'CongressmanLegislature' => CongressmanLegislature::class,
            'CongressmanBudget' => CongressmanBudget::class,
            'User' => User::class,
            'Congressman' => Congressman::class,
        ];

        $permaDeleted = false;

        return collect(explode('->', $relation))
            ->push('last')
            ->reduce(
                function ($carry, $item) use ($classArray, $modelId, &$permaDeleted) {
                    if (!$permaDeleted) {
                        if ($previous = $carry['previous']) {
                            $className = Str::ucfirst($camel = Str::camel($previous));

                            if (!($previousModel = $carry['model'])) {
                                $id = $modelId;
                            } else {
                                $id = $previousModel->{Str::snake($camel) . '_id'};
                            }

                            if (
                                !($model = $classArray[$className]
                                    ::withoutGlobalScopes()
                                    ->find($id))
                            ) {
                                $currentCamel = Str::camel($item);

                                $audit = Audit::where('auditable_id', $id)
                                    ->where('auditable_type', 'like', '%' . $className)
                                    ->when(Str::snake($currentCamel) != 'last', function (
                                        $query
                                    ) use ($currentCamel) {
                                        return $query->where(
                                            'new_values',
                                            'like',
                                            '%' . Str::snake($currentCamel) . '_id' . '%'
                                        );
                                    })
                                    ->orderBy('created_at', 'desc')
                                    ->first();

                                if (!$audit) {
                                    $permaDeleted = true;
                                    return ['previous' => $item, 'model' => null];
                                }

                                //If last is delete(new_values empty), then get the old_values, which are all the values
                                $model = !empty($audit->new_values)
                                    ? $audit->new_values
                                    : $audit->old_values;
                            }

                            return ['previous' => $item, 'model' => $model];
                        } else {
                            return ['previous' => $item, 'model' => null];
                        }
                    } else {
                        return ['previous' => $item, 'model' => null];
                    }
                },
                ['previous' => null, 'model' => null]
            )['model'];
    }

    public function getReferenceAttribute()
    {
        $auditable = $this->auditable;
        $old = $this->old_values;

        $array = [];

        switch ($this->entity) {
            case 'User':
                $congressman = $auditable->congressman;

                if (isset($congressman) && isset($congressman->name)) {
                    $array[] = [
                        'field' => 'Deputado',
                        'value' => $auditable->congressman->name,
                    ];
                }

                if (isset($auditable->email)) {
                    $array[] = [
                        'field' => 'Usuário',
                        'value' => $auditable->email,
                    ];
                }

                break;
            case 'CongressmanLegislature':
                $congressman = $auditable->congressman;

                if (isset($congressman) && isset($congressman->name)) {
                    $array[] = [
                        'field' => 'Deputado',
                        'value' => $auditable->congressman->name,
                    ];
                }
                break;
            case 'ProviderBlockPeriod':
                $provider = $this->getLastModel(
                    'providerBlockPeriod->provider',
                    $this->auditable_id
                );

                if (isset($provider->name)) {
                    $array[] = [
                        'field' => 'Nome',
                        'value' => $provider->name,
                    ];
                }
                if (isset($provider->cpf_cnpj)) {
                    $array[] = [
                        'field' => 'CPF/CNPJ',
                        'value' => $provider->cpf_cnpj,
                    ];
                }

                break;
            case 'Budget':
                $congressman = $auditable->congressman;

                if (isset($congressman) && isset($congressman->name)) {
                    $array[] = [
                        'field' => 'Deputado',
                        'value' => $auditable->congressman->name,
                    ];
                }
                break;
            case 'EntryDocument':
                $congressman = $this->getLastModel(
                    'entryDocument->entry->congressmanBudget->congressmanLegislature->congressman',
                    $this->auditable_id
                );

                if (isset($congressman->name)) {
                    $array[] = [
                        'field' => 'Deputado',
                        'value' => $congressman->name,
                    ];
                }

                $entry = $this->getLastModel('entryDocument->entry', $this->auditable_id);

                if (isset($entry->date)) {
                    $array[] = [
                        'field' => 'Data',
                        'value' =>
                            $entry->date instanceof Carbon
                                ? $entry->date->format('d/m/Y')
                                : Carbon::create($entry->date)->format('d/m/Y'),
                    ];
                }

                if (isset($entry->value)) {
                    $array[] = [
                        'field' => 'Valor',
                        'value' => to_reais(abs($entry->value)),
                    ];
                }

                if (isset($entry->object)) {
                    $array[] = [
                        'field' => 'Objeto',
                        'value' => $entry->object,
                    ];
                }

                $provider = $this->getLastModel(
                    'entryDocument->entry->provider',
                    $this->auditable_id
                );

                if (isset($provider->name)) {
                    $array[] = [
                        'field' => 'Fornecedor',
                        'value' => $provider->name,
                    ];
                }
                if (isset($provider->cpf_cnpj)) {
                    $array[] = [
                        'field' => 'CPF/CNPJ',
                        'value' => $provider->cpf_cnpj,
                    ];
                }
                $attachedFile = AttachedFile::where('fileable_id', $this->auditable_id)->first();

                if (isset($attachedFile->original_name)) {
                    $array[] = [
                        'field' => 'Arquivo',
                        'value' => $attachedFile->original_name,
                    ];
                }

                break;
            case 'Legislature':
                if (isset($auditable->year_start) && isset($auditable->year_end)) {
                    $array[] = [
                        'field' => 'Legislatura',
                        'value' => $auditable->year_start . ' -' . $auditable->year_end,
                    ];
                }
                break;
            case 'CostCenter':
                if (isset($auditable->code) && isset($auditable->name)) {
                    $array[] = [
                        'field' => 'Nome',
                        'value' => $auditable->code . ' - ' . $auditable->name,
                    ];
                }
                break;
            case 'CongressmanSettings':
                $congressman = $auditable->congressman;

                if (isset($congressman) && isset($congressman->name)) {
                    $array[] = [
                        'field' => 'Deputado',
                        'value' => $auditable->congressman->name,
                    ];
                }
                break;
            case 'EntryComment':
                $entry = $this->getLastModel('entryComment->entry', $this->auditable_id);

                if (isset($entry->date)) {
                    $array[] = [
                        'field' => 'Data',
                        'value' =>
                            $entry->date instanceof Carbon
                                ? $entry->date->format('d/m/Y')
                                : Carbon::create($entry->date)->format('d/m/Y'),
                    ];
                }

                if (isset($entry->value)) {
                    $array[] = [
                        'field' => 'Valor',
                        'value' => to_reais(abs($entry->value)),
                    ];
                }

                if (isset($entry->object)) {
                    $array[] = [
                        'field' => 'Objeto',
                        'value' => $entry->object,
                    ];
                }

                $provider = $this->getLastModel(
                    'entryComment->entry->provider',
                    $this->auditable_id
                );

                if (isset($provider->name)) {
                    $array[] = [
                        'field' => 'Fornecedor',
                        'value' => $provider->name,
                    ];
                }

                if (isset($provider->cpf_cnpj)) {
                    $array[] = [
                        'field' => 'CPF/CNPJ',
                        'value' => $provider->cpf_cnpj,
                    ];
                }

                break;
            case 'EntryType':
                if (isset($auditable->name)) {
                    $array[] = [
                        'field' => 'Nome',
                        'value' => $auditable->name,
                    ];
                }
                break;
            case 'Provider':
                if (isset($auditable->name)) {
                    $array[] = [
                        'field' => 'Nome',
                        'value' => $auditable->name,
                    ];
                }
                if (isset($auditable->cpf_cnpj)) {
                    $array[] = [
                        'field' => 'CPF/CNPJ',
                        'value' => $auditable->cpf_cnpj,
                    ];
                }
                break;
            case 'Party':
                if (isset($auditable->name)) {
                    $array[] = [
                        'field' => 'Nome',
                        'value' => $auditable->name,
                    ];
                }
                if (isset($auditable->code)) {
                    $array[] = [
                        'field' => 'Código',
                        'value' => $auditable->code,
                    ];
                }
                break;
            case 'CongressmanBudget':
                //TODO: [2021-10-14 14:34:11] production.ERROR: Trying to get property 'congressman' of non-object (View: /var/www/docigp/resources/views/livewire/audits/index.blade.php)
                if ($congressman = $auditable->congressman) {
                    if (isset($congressman->name)) {
                        $array[] = [
                            'field' => 'Deputado',
                            'value' => $auditable->congressman->name,
                        ];
                    }
                }

                if ($budget = $auditable->budget) {
                    if (isset($budget->date)) {
                        $array[] = [
                            'field' => 'Mês',
                            'value' => $auditable->budget->date->format('d/m'),
                        ];
                    }
                }
                break;
            case 'Entry':
                $congressman = $this->getLastModel(
                    'entry->congressmanBudget->congressmanLegislature->congressman',
                    $this->auditable_id
                );

                if (isset($congressman->name)) {
                    $array[] = [
                        'field' => 'Deputado',
                        'value' => $congressman->name,
                    ];
                }

                $entry = $this->getLastModel('entry', $this->auditable_id);

                if (isset($entry->date)) {
                    $array[] = [
                        'field' => 'Data',
                        'value' =>
                            $entry->date instanceof Carbon
                                ? $entry->date->format('d/m/Y')
                                : Carbon::create($entry->date)->format('d/m/Y'),
                    ];
                }

                if (isset($entry->value)) {
                    $array[] = [
                        'field' => 'Valor',
                        'value' => to_reais(abs($entry->value)),
                    ];
                }

                if (isset($entry->object)) {
                    $array[] = [
                        'field' => 'Objeto',
                        'value' => $entry->object,
                    ];
                }

                $provider = $this->getLastModel('entry->provider', $this->auditable_id);

                if (isset($provider->name)) {
                    $array[] = [
                        'field' => 'Fornecedor',
                        'value' => $provider->name,
                    ];
                }

                if (isset($provider->cpf_cnpj)) {
                    $array[] = [
                        'field' => 'CPF/CNPJ',
                        'value' => $provider->cpf_cnpj,
                    ];
                }

                break;
            case 'Congressman':
                if (isset($auditable->name)) {
                    $array[] = [
                        'field' => 'Deputado',
                        'value' => $auditable->name,
                    ];
                }
                break;
        }

        return $array;
    }

    public function getOldValuesAttribute()
    {
        return json_decode($this->attributes['old_values']);
    }

    public function getNewValuesAttribute()
    {
        return json_decode($this->attributes['new_values']);
    }
}
