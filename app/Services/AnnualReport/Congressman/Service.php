<?php

namespace App\Services\AnnualReport\Congressman;

use App\Models\Budget;
use App\Models\Congressman;
use App\Models\CongressmanBudget;
use App\Models\CongressmanLegislature;
use App\Models\CostCenter;
use App\Models\Entry;
use App\Models\Legislature;
use App\Support\Constants;
use App\Data\Repositories\CostCenters as CostCentersRepository;
use Carbon\Carbon;
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

    public function init($year = '2019', $congressman)
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

        $this->congressman = $congressman;

        $this->period = CarbonPeriod::create($year . '-01-01', '1 month', $year . '-12-01');

        $this->costCentersRows = app(CostCentersRepository::class)->costCenterLimitsTable();
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
                $congressmanLegislatures = CongressmanLegislature::where(
                    'congressman_id',
                    $this->congressman->id
                )
                    ->where('legislature_id', $this->legislature->id)
                    ->get();

                if ($congressmanLegislatures) {
                    $percentage = 0;
                    foreach ($congressmanLegislatures as $congressmanLegislature) {
                        if (
                            $congressmanBudget = CongressmanBudget::where('budget_id', $budget->id)
                                ->where('congressman_legislature_id', $congressmanLegislature->id)
                                ->first()
                        ) {
                            $percentage += $congressmanBudget->percentage;

                            //Calcula crédito
                            Entry::where('cost_center_id', $this->creditCostCenter->id)
                                ->where('congressman_budget_id', $congressmanBudget->id)
                                ->get()
                                ->each(function ($item) {
                                    $this->creditTotal += abs($item->value);
                                });

                            //Calcula devolução
                            Entry::where('cost_center_id', $this->refundCostCenter->id)
                                ->where('congressman_budget_id', $congressmanBudget->id)
                                ->get()
                                ->each(function ($item) {
                                    $this->refundTotal += abs($item->value);
                                });
                        }
                    }

                    $row->push(number_format($percentage, 2, '.', ''));
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

                $montComProblema = Carbon::create('2020-07-01');

                if ($budget) {
                    $congressmanLegislatures = CongressmanLegislature::where(
                        'congressman_id',
                        $this->congressman->id
                    )
                        ->where('legislature_id', $this->legislature->id)
                        ->get();

                    if ($congressmanLegislatures) {
                        $partialSum = 0;

                        foreach ($congressmanLegislatures as $congressmanLegislature) {
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
                                $entries = Entry::selectRaw('sum(value) as soma')
                                    ->where('congressman_budget_id', $congressmanBudget->id)
                                    ->whereNotNull('published_at')
                                    ->whereIn('cost_center_id', $costCenter['ids'])
                                    ->first();

                                $partialSum += abs($entries->soma);

                                $total += abs($partialSum);
                            }
                        }

                        $soma = to_reais(abs($partialSum));

                        $row->push($soma);
                    } else {
                        $row->push(to_reais(0));
                    }
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
                $congressmanLegislatures = CongressmanLegislature::where(
                    'congressman_id',
                    $this->congressman->id
                )
                    ->where('legislature_id', $this->legislature->id)
                    ->get();

                if ($congressmanLegislatures) {
                    $partialSum = 0;

                    foreach ($congressmanLegislatures as $congressmanLegislature) {
                        if (
                            $congressmanBudget = CongressmanBudget::where('budget_id', $budget->id)
                                ->where('congressman_legislature_id', $congressmanLegislature->id)
                                ->first()
                        ) {
                            $entries = Entry::selectRaw('sum(value) as soma');

                            $entries->orWhere(function ($query) {
                                foreach ($this->costCentersRows as $costCenter) {
                                    $query->orWhereIn('cost_center_id', $costCenter['ids']);
                                }
                            });

                            $entries
                                ->where('congressman_budget_id', $congressmanBudget->id)
                                ->whereNotNull('published_at');

                            $partialSum += abs($entries->first()->soma);
                        }
                    }
                    $soma = to_reais(abs($partialSum));

                    $row->push($soma);
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

    public function getMainTable($year = '2019', $congressman)
    {
        //        $year = '2019';

        $this->init($year, $congressman);
        $table = collect([]);

        $table = $this->fillFirstRow($table);

        //Gerar segunda linha de percentual
        $table = $this->fillPercentageRow($table);
        //Fim da segunda linha

        //Gerar linhas do meio
        $table = $this->fillInsideRows($table);
        //Fim das linhas do meio

        //Gerar última linha
        $table = $this->fillTotalsRow($table);
        //Fim da última linha

        //        dump('creditTotal');
        //        dump($this->creditTotal);
        //        dump('refundTotal');
        //        dump($this->refundTotal);
        //        dump('spentTotal');
        //        dump($this->spentTotal);

        return [
            'congressman' => $this->congressman,
            'year' => $year,
            'mainTable' => $table,
            'totalsTable' => [
                'creditTotal' => to_reais($this->creditTotal),
                'refundTotal' => to_reais($this->refundTotal),
                'spentTotal' => to_reais($this->spentTotal),
                'spentAndRefundTotal' => to_reais($this->spentTotal + $this->refundTotal),
                'situation' =>
                    to_reais($this->creditTotal) == to_reais($this->refundTotal + $this->spentTotal)
                        ? 'REGULAR'
                        : 'IRREGULAR',
            ],
        ];
    }

    public function costCenterTable()
    {
        $abbreviations = [
            'I' => 'Passagens',
            'II' => 'Serv. Postais',
            'III' => 'Manut. Gab.',
            'IV' => 'Custeio Gab.',
            'V' => 'Alimentação',
            'VI.a' => 'Loc. veículos',
            'VI.b' => 'Locomoção',
            'VII' => 'Combustíveis',
            'VIII' => 'Divulgação',
            'IX' => 'Cursos',
            'X' => 'Diárias',
            'XI' => 'Tarifas',
        ];

        $allResponse = collect();

        $i = 1;
        while ($parent = CostCenter::where('code', $roman = NumConvert::roman($i))->first()) {
            if ($i == 6) {
                $costCenters = CostCenter::where('parent_code', $roman)->get();

                $costCenters->each(function ($costCenter) use (
                    $abbreviations,
                    $allResponse,
                    $roman,
                    $i,
                    $parent
                ) {
                    $costCenterArrayResponse = [
                        'abbreviation' => $abbreviations[$costCenter->code] ?? '',
                        'number' => $i,
                        'roman' => $costCenter->code,
                        'ids' => collect($costCenter->id),
                    ];

                    $allResponse->push($costCenterArrayResponse);
                });
            } else {
                $costCenterIds = CostCenter::where('code', $roman)
                    ->orWhere('parent_code', $roman)
                    ->get()
                    ->map(function ($item) {
                        return $item->id;
                    });

                $collection = collect($costCenterIds);

                $costCenterArrayResponse = [
                    'abbreviation' => $abbreviations[$roman] ?? '',
                    'number' => $i,
                    'roman' => $roman,
                    'ids' => $collection,
                ];

                $allResponse->push($costCenterArrayResponse);
            }

            $i++;
        }

        return $allResponse;
    }
}
