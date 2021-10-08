<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;

class Audit extends Model
{
    protected $controlCreatedBy = false;

    protected $controlUpdatedBy = false;

    protected $with = ['user'];

    protected $dates = ['created_at'];

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
        return $route = app('router')
            ->getRoutes()
            ->match(app('request')->create($this->url, 'POST'))
            ->getName();
    }

    public function getActivityAttribute()
    {
        $route = $this->route_name;
        $url = $this->url;

        $activity = '';

        if (!$route) {
            $activity = 'Login';
        }

        if (Str::startsWith($route, 'logout')) {
            $activity = 'Logout';
        }

        if (Str::endsWith($route, 'congressman-legislatures.remove-from-legislature')) {
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

        return collect(explode('->', $relation))
            ->push('last')
            ->reduce(
                function ($carry, $item) use ($classArray, $modelId) {
                    if ($previous = $carry['previous']) {
                        $className = Str::ucfirst($camel = Str::camel($previous));

                        if (!($previousModel = $carry['model'])) {
                            $id = $modelId;
                        } else {
                            //                            dump(Str::snake($camel) . '_id');
                            $id = $previousModel->{Str::snake($camel) . '_id'};
                        }

                        if (!($model = $classArray[$className]::withoutGlobalScopes()->find($id))) {
                            $currentCamel = Str::camel($item);

                            $audit = Audit::where('auditable_id', $id)
                                ->where('auditable_type', 'like', '%' . $className)

                                ->when(Str::snake($currentCamel) != 'last', function ($query) use (
                                    $currentCamel
                                ) {
                                    return $query->where(
                                        'new_values',
                                        'like',
                                        '%' . Str::snake($currentCamel) . '_id' . '%'
                                    );
                                })

                                ->orderBy('created_at', 'desc')
                                ->first();

                            //                            if (!$audit) {
                            //                                dd(
                            //                                    $id .
                            //                                        ' - ' .
                            //                                        $className .
                            //                                        ' - ' .
                            //                                        Str::snake($currentCamel) .
                            //                                        ' - '
                            //                                );
                            //                            }

                            //If last is delete(new_values empty), then get the old_values, which are all the values
                            $model = !empty($audit->new_values)
                                ? $audit->new_values
                                : $audit->old_values;
                        }

                        return ['previous' => $item, 'model' => $model];
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
                //                TESTADO
                if ($auditable->congressman_id) {
                    $array[] = [
                        'field' => 'Deputado',
                        'value' => $auditable->congressman->name,
                    ];
                }
                $array[] = [
                    'field' => 'Usuário',
                    'value' => $auditable->email,
                ];

                break;
            case 'CongressmanLegislature':
                $array[] = [
                    'field' => 'Deputado',
                    'value' => $auditable->congressman->name,
                ];
                break;
            case 'ProviderBlockPeriod':
                $provider = $this->getLastModel(
                    'providerBlockPeriod->provider',
                    $this->auditable_id
                );

                $array[] = [
                    'field' => 'Nome',
                    'value' => $provider->name,
                ];
                $array[] = [
                    'field' => 'CPF/CNPJ',
                    'value' => $provider->cpf_cnpj,
                ];

                break;
            case 'Budget':
                $array[] = [
                    'field' => 'Deputado',
                    'value' => $auditable->congressman->name,
                ];
                break;
            case 'EntryDocument':
                $array[] = [
                    'field' => 'Deputado',
                    'value' => $this->getLastModel(
                        'entryDocument->entry->congressmanBudget->congressmanLegislature->congressman',
                        $this->auditable_id
                    )->name,
                ];

                $entry = $this->getLastModel('entryDocument->entry', $this->auditable_id);

                $array[] = [
                    'field' => 'Data',
                    'value' =>
                        $entry->date instanceof Carbon
                            ? $entry->date->format('d/m/Y')
                            : Carbon::create($entry->date)->format('d/m/Y'),
                ];
                $array[] = [
                    'field' => 'Valor',
                    'value' => to_reais(abs($entry->value)),
                ];

                $array[] = [
                    'field' => 'Objeto',
                    'value' => $entry->object,
                ];

                $provider = $this->getLastModel(
                    'entryDocument->entry->provider',
                    $this->auditable_id
                );

                $array[] = [
                    'field' => 'Fornecedor',
                    'value' => $provider->name,
                ];
                $array[] = [
                    'field' => 'CPF/CNPJ',
                    'value' => $provider->cpf_cnpj,
                ];

                $attachedFile = AttachedFile::where('fileable_id', $this->auditable_id)->first();

                $array[] = [
                    'field' => 'Arquivo',
                    'value' => $attachedFile->original_name,
                ];

                break;
            case 'Legislature':
                //                TESTADO
                $array[] = [
                    'field' => 'Legislatura',
                    'value' => $auditable->year_start . ' -' . $auditable->year_end,
                ];
                break;
            case 'CostCenter':
                $array[] = [
                    'field' => 'Nome',
                    'value' => $auditable->code . ' - ' . $auditable->name,
                ];
                break;
            case 'CongressmanSettings':
                $array[] = [
                    'field' => 'Deputado',
                    'value' => $auditable->congressman->name,
                ];
                break;
            case 'EntryComment':
                $entry = $this->getLastModel('entryComment->entry', $this->auditable_id);

                if (!$entry) {
                    dd($entry);
                }

                $array[] = [
                    'field' => 'Data',
                    'value' =>
                        $entry->date instanceof Carbon
                            ? $entry->date->format('d/m/Y')
                            : Carbon::create($entry->date)->format('d/m/Y'),
                ];
                $array[] = [
                    'field' => 'Valor',
                    'value' => to_reais(abs($entry->value)),
                ];

                $array[] = [
                    'field' => 'Objeto',
                    'value' => $entry->object,
                ];

                $provider = $this->getLastModel(
                    'entryComment->entry->provider',
                    $this->auditable_id
                );

                $array[] = [
                    'field' => 'Fornecedor',
                    'value' => $provider->name,
                ];
                $array[] = [
                    'field' => 'CPF/CNPJ',
                    'value' => $provider->cpf_cnpj,
                ];

                break;
            case 'EntryType':
                $array[] = [
                    'field' => 'Nome',
                    'value' => $auditable->name,
                ];
                break;
            case 'Provider':
                $array[] = [
                    'field' => 'Nome',
                    'value' => $auditable->name,
                ];
                $array[] = [
                    'field' => 'CPF/CNPJ',
                    'value' => $auditable->cpf_cnpj,
                ];
                break;
            case 'Party':
                $array[] = [
                    'field' => 'Nome',
                    'value' => $auditable->name,
                ];
                $array[] = [
                    'field' => 'Código',
                    'value' => $auditable->code,
                ];
                break;
            case 'CongressmanBudget':
                $array[] = [
                    'field' => 'Deputado',
                    'value' => $auditable->congressman->name,
                ];
                $array[] = [
                    'field' => 'Mês',
                    'value' => $auditable->budget->date->format('d/m'),
                ];
                break;
            case 'Entry':
                $array[] = [
                    'field' => 'Deputado',
                    'value' => $this->getLastModel(
                        'entry->congressmanBudget->congressmanLegislature->congressman',
                        $this->auditable_id
                    )->name,
                ];

                $entry = $this->getLastModel('entry', $this->auditable_id);

                $array[] = [
                    'field' => 'Data',
                    'value' =>
                        $entry->date instanceof Carbon
                            ? $entry->date->format('d/m/Y')
                            : Carbon::create($entry->date)->format('d/m/Y'),
                ];
                $array[] = [
                    'field' => 'Valor',
                    'value' => to_reais(abs($entry->value)),
                ];

                $array[] = [
                    'field' => 'Objeto',
                    'value' => $entry->object,
                ];

                $provider = $this->getLastModel('entry->provider', $this->auditable_id);

                $array[] = [
                    'field' => 'Fornecedor',
                    'value' => $provider->name,
                ];
                $array[] = [
                    'field' => 'CPF/CNPJ',
                    'value' => $provider->cpf_cnpj,
                ];

                break;
            case 'Congressman':
                $array[] = [
                    'field' => 'Deputado',
                    'value' => $auditable->name,
                ];
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
