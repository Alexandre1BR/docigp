<?php

namespace App\Data\Repositories;

use App\Data\Models\Entry;
use Carbon\Carbon;
use App\Data\Models\Congressman;
use App\Data\Models\CongressmanBudget;
use App\Data\Traits\RepositoryActionable;

class CongressmanBudgets extends Repository
{
    use RepositoryActionable;

    /**
     * @var string
     */
    protected $model = CongressmanBudget::class;

    public function allFor($congressmanId)
    {
        return $this->applyFilter(
            $this->newQuery()
                ->join(
                    'congressman_legislatures',
                    'congressman_legislatures.id',
                    'congressman_budgets.congressman_legislature_id'
                )
                ->where(
                    'congressman_legislatures.congressman_id',
                    $congressmanId
                )
        );
    }

    protected function buildPendenciesArray($congressmanBudget)
    {
        $pendencies = [];

        if ((float) $congressmanBudget['percentage'] === 0.0) {
            $pendencies[] = 'definir percentual';
        }

        if (
            (float) $congressmanBudget['percentage'] !== 0.0 &&
            (float) $congressmanBudget['sum_credit'] === 0.0
        ) {
            $pendencies[] = 'depositar';
        }

        if ($congressmanBudget['missing_verification']) {
            $pendencies[] = 'verificar lançamentos';
        }

        if ($congressmanBudget['missing_analysis']) {
            $pendencies[] = 'analisar lançamentos';
        }

        if (blank($congressmanBudget['analysed_at'])) {
            $pendencies[] = 'analisar mês';
        }

        if ((float) $congressmanBudget['balance'] > 0.0) {
            $pendencies[] = 'saldo positivo';
        }

        if ((float) $congressmanBudget['balance'] < 0.0) {
            $pendencies[] = 'saldo negativo';
        }

        if (blank($congressmanBudget['published_at'])) {
            $pendencies[] = 'publicar';
        }

        return $pendencies;
    }

    public function transform($data)
    {
        $this->addTransformationPlugin(function ($congressmanBudget) {
            $congressmanBudget['year'] = Carbon::parse(
                $congressmanBudget['budget']['date']
            )->year;

            $congressmanBudget['month'] = sprintf(
                '%02d',
                Carbon::parse($congressmanBudget['budget']['date'])->month
            );

            $congressmanBudget['state_value_formatted'] = to_reais(
                $congressmanBudget['budget']['value']
            );

            $congressmanBudget['value_formatted'] = to_reais(
                $congressmanBudget['value']
            );

            $congressmanBudget['sum_debit_formatted'] = to_reais(
                $congressmanBudget['sum_debit']
            );

            $congressmanBudget['sum_credit_formatted'] = to_reais(
                $congressmanBudget['sum_credit']
            );

            $congressmanBudget['balance'] =
                $congressmanBudget['sum_credit'] +
                $congressmanBudget['sum_debit'];

            $congressmanBudget['balance_formatted'] = to_reais(
                $congressmanBudget['balance']
            );

            $congressmanBudget['percentage_formatted'] =
                $congressmanBudget['percentage'] . '%';

            $congressmanBudget['pendencies'] = $this->buildPendenciesArray(
                $congressmanBudget
            );

            return $congressmanBudget;
        });

        return parent::transform($data);
    }

    public function deposit($modelId)
    {
        $this->findById($modelId)->deposit();
    }

    /**
     * @param $callable
     * @return mixed
     */
    public function withGlobalScopesDisabled($callable)
    {
        Congressman::disableGlobalScopes();

        $result = $callable();

        Congressman::enableGlobalScopes();

        return $result;
    }

    public function updateAllEntriesFor($id)
    {
        CongressmanBudget::find($id)
            ->congressman->congressmanBudgets()
            ->orderBy('id', 'asc')
            ->get()
            ->each(function ($congressmanBudget) {
                $congressmanBudget->updateTransportEntries();
            });
    }
}
