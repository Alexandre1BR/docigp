<?php

namespace App\Services\AnnualReport\General;

use App\Data\Models\Budget;
use App\Data\Models\Congressman;
use App\Data\Models\CongressmanBudget;
use App\Data\Models\CongressmanLegislature;
use App\Data\Models\CostCenter;
use App\Data\Models\Entry;
use App\Data\Models\Legislature;
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
        $row->push('JAN');
        $row->push('FEV');
        $row->push('MAR');
        $row->push('ABR');
        $row->push('MAI');
        $row->push('JUN');
        $row->push('JUL');
        $row->push('AGO');
        $row->push('SET');
        $row->push('OUT');
        $row->push('NOV');
        $row->push('DEZ');

        $row->push('TOTAL');

        $table->push($row);

        return $table;
    }

    public function fillPercentageRow($table)
    {
        $row = collect([]);
        $row->push('Solicitado(%)');
        foreach ($this->period as $month) {
            $budget = Budget::where('date', $month)->first();

            if ($budget) {
                $congressmanLegislature = CongressmanLegislature::where(
                    'congressman_id',
                    $this->congressman->id
                )
                    ->where('legislature_id', $this->legislature->id)
                    ->first();

                if ($congressmanLegislature) {
                    if (
                        $congressmanBudget = CongressmanBudget::where(
                            'budget_id',
                            $budget->id
                        )
                            ->where(
                                'congressman_legislature_id',
                                $congressmanLegislature->id
                            )
                            ->first()
                    ) {
                        $row->push(
                            number_format(
                                $congressmanBudget->percentage,
                                2,
                                '.',
                                ''
                            )
                        );

                        //Calcula crédito
                        Entry::where(
                            'cost_center_id',
                            $this->creditCostCenter->id
                        )
                            ->where(
                                'congressman_budget_id',
                                $congressmanBudget->id
                            )
                            ->get()
                            ->each(function ($item) {
                                $this->creditTotal += abs($item->value);
                            });

                        //Calcula devolução
                        Entry::where(
                            'cost_center_id',
                            $this->refundCostCenter->id
                        )
                            ->where(
                                'congressman_budget_id',
                                $congressmanBudget->id
                            )
                            ->get()
                            ->each(function ($item) {
                                $this->refundTotal += abs($item->value);
                            });
                    } else {
                        $row->push(number_format(0, 2, '.', ''));
                    }
                } else {
                    $row->push(number_format(0, 2, '.', ''));
                }
            } else {
                $row->push(number_format(0, 2, '.', ''));
            }
        }
        $row->push('');
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
                        //                        ->join('congressman_legislatures','congressman_legislatures.')
                        ->join(
                            'congressman_budgets',
                            'congressman_budgets.id',
                            '=',
                            'entries.congressman_budget_id'
                        )
                        ->where('congressman_budgets.budget_id', $budget->id)
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
        foreach ($this->period as $month) {
            $budget = Budget::where('date', $month)->first();

            if ($budget) {
                $congressmanLegislature = CongressmanLegislature::where(
                    'congressman_id',
                    $this->congressman->id
                )
                    ->where('legislature_id', $this->legislature->id)
                    ->first();

                if ($congressmanLegislature) {
                    if (
                        $congressmanBudget = CongressmanBudget::where(
                            'budget_id',
                            $budget->id
                        )
                            ->where(
                                'congressman_legislature_id',
                                $congressmanLegislature->id
                            )
                            ->first()
                    ) {
                        $entries = Entry::selectRaw('sum(value) as soma');

                        $entries->orWhere(function ($query) {
                            foreach ($this->costCentersRows as $costCenter) {
                                $query->orWhereIn(
                                    'cost_center_id',
                                    $costCenter['ids']
                                );
                            }
                        });

                        $entries
                            ->where(
                                'congressman_budget_id',
                                $congressmanBudget->id
                            )
                            ->whereNotNull('published_at');

                        $total = abs($entries->first()->soma);

                        $row->push(to_reais($total));
                    } else {
                        $row->push(to_reais(0));
                    }
                } else {
                    $row->push(to_reais(0));
                }
            } else {
                $row->push(to_reais(0));
            }
        }
        $row->push('');
        $table->push($row);

        return $table;
    }

    public function getMainTable($year = '2019')
    {
        //        $year = '2019';

        $this->init($year);
        $table = collect([]);

        $table = $this->fillFirstRow($table);

        //Gerar linhas do meio
        $table = $this->fillInsideRows($table);
        //Fim das linhas do meio

        //Gerar última linha
        //        $table = $this->fillTotalsRow($table);
        //Fim da última linha

        //        dump('creditTotal');
        //        dump($this->creditTotal);
        //        dump('refundTotal');
        //        dump($this->refundTotal);
        //        dump('spentTotal');
        //        dump($this->spentTotal);

        //        dd($table);

        return [
            //            'congressman' => $this->congressman,
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
                        : 'IRREGULAR'
            ]
        ];
    }
}