<?php

namespace App\Services\AnnualReport\General;

use App\Models\Budget;
use App\Models\Congressman;
use App\Models\CongressmanBudget;
use App\Models\CongressmanLegislature;
use App\Models\CostCenter;
use App\Models\Entry;
use App\Models\Legislature;
use App\Data\Repositories\CostCenters as CostCentersRepository;
use App\Support\Constants;
use Carbon\CarbonPeriod;
use HnhDigital\LaravelNumberConverter\Facade as NumConvert;

class Service
{
    public $spentTotal;
    public $creditTotal;
    public $creditCostCenter;
    public $refundTotal;
    public $refundCostCenter;
    public $legislature;
    public $period;
    public $costCentersRows;
    public $congressman;

    public $months = [
        'JAN',
        'FEV',
        'MAR',
        'ABR',
        'MAI',
        'JUN',
        'JUL',
        'AGO',
        'SET',
        'OUT',
        'NOV',
        'DEZ',
    ];

    public function init($year = '2019')
    {
        $this->spentTotal = 0;
        $this->creditTotal = 0;
        $this->creditCostCenter = CostCenter::where(
            'code',
            Constants::COST_CENTER_CREDIT_ID
        )->first();
        $this->refundTotal = 0;
        $this->refundCostCenter = CostCenter::where(
            'code',
            Constants::COST_CENTER_REFUND_CODE
        )->first();

        $this->legislature = Legislature::where('year_start', '<=', $year)
            ->where('year_end', '>=', $year)
            ->first();

        $this->period = CarbonPeriod::create(
            $year . '-01-01',
            '1 month',
            $year . '-12-01'
        );

        $this->costCentersRows = app(
            CostCentersRepository::class
        )->costCenterLimitsTable();
    }

    public function fillFirstRow($table)
    {
        $row = collect([]);

        $row->push('');

        //Meses
        foreach ($this->months as $month) {
            $row->push($month);
        }

        $row->push('TOTAL');

        $table->push($row);

        return $table;
    }

    public function fillInsideRows($table)
    {
        foreach ($this->costCentersRows as $costCenter) {
            $row = collect([]);
            $total = 0;
            $row->push($costCenter['abbreviation']);

            foreach ($this->period as $month) {
                $budget = Budget::where('date', $month)->first();

                if ($budget) {
                    $entries = Entry::selectRaw('sum(entries.value) as soma')
                        ->join(
                            'congressman_budgets',
                            'congressman_budgets.id',
                            '=',
                            'entries.congressman_budget_id'
                        )
                        ->where('congressman_budgets.budget_id', $budget->id)
                        ->whereNotNull('congressman_budgets.published_at')
                        ->whereNotNull('entries.published_at')
                        ->whereIn('cost_center_id', $costCenter['ids'])
                        ->first();

                    $total += abs($entries->soma);

                    $soma = to_reais(abs($entries->soma));

                    $row->push($soma);
                } else {
                    $row->push(to_reais(0));
                }
            }

            $this->spentTotal += $total;
            $row->push(to_reais($total));

            $table->push($row);
        }

        return $table;
    }

    public function fillTotalsRow($table)
    {
        $row = collect([]);
        $row->push('TOTAL');
        for ($i = 1; $i <= 12; $i++) {
            $totalPerMonth = 0;
            foreach ($table as $item) {
                if (!in_array($item[$i], $this->months)) {
                    $totalPerMonth += without_reais($item[$i]);
                }
            }
            $row->push(to_reais($totalPerMonth));
        }

        $row->push(to_reais($this->spentTotal));
        $table->push($row);

        return $table;
    }

    public function calculateTotals($table)
    {
        foreach ($this->period as $month) {
            $budget = Budget::where('date', $month)->first();

            if ($budget) {
                //Crédito
                Entry::selectRaw('sum(entries.value) as soma')
                    ->join(
                        'congressman_budgets',
                        'congressman_budgets.id',
                        '=',
                        'entries.congressman_budget_id'
                    )
                    ->where('congressman_budgets.budget_id', $budget->id)
                    ->where('cost_center_id', '=', $this->creditCostCenter->id)
                    ->get()
                    ->each(function ($item) use ($budget) {
                        $this->creditTotal += abs($item->soma);
                    });

                //devolução
                Entry::selectRaw('sum(entries.value) as soma')
                    ->join(
                        'congressman_budgets',
                        'congressman_budgets.id',
                        '=',
                        'entries.congressman_budget_id'
                    )
                    ->where('congressman_budgets.budget_id', $budget->id)
                    ->where('cost_center_id', '=', $this->refundCostCenter->id)
                    ->get()
                    ->each(function ($item) {
                        $this->refundTotal += abs($item->soma);
                    });
            }
        }
    }

    public function getMainTable($year = '2019')
    {
        $this->init($year);
        $table = collect([]);

        $table = $this->fillFirstRow($table);

        //Gerar linhas do meio
        $table = $this->fillInsideRows($table);
        //Fim das linhas do meio

        //Gerar última linha
        $table = $this->fillTotalsRow($table);
        //Fim da última linha

        $this->calculateTotals($table);

        return [
            'year' => $year,
            'mainTable' => $table,
            'totalsTable' => [
                'creditTotal' => to_reais($this->creditTotal),
                'refundTotal' => to_reais($this->refundTotal),
                'spentTotal' => to_reais($this->spentTotal),
                'spentAndRefundTotal' => to_reais(
                    $this->spentTotal + $this->refundTotal
                ),
                'situation' =>
                    to_reais($this->creditTotal) ==
                    to_reais($this->refundTotal + $this->spentTotal)
                        ? 'REGULAR'
                        : 'IRREGULAR',
            ],
        ];
    }
}
