<?php

namespace App\Data\Repositories;

use App\Support\Constants;
use App\Data\Models\CostCenter;
use Cache;
use HnhDigital\LaravelNumberConverter\Facade as NumConvert;

class CostCenters extends Repository
{
    /**
     * @var string
     */
    protected $model = CostCenter::class;

    public function filterControlTypes()
    {
        if (current_user() && !current_user()->can('entries:control-update')) {
            $this->addCustomQuery(function ($query) {
                $query->whereNotIn(
                    'code',
                    Constants::COST_CENTER_CONTROL_CODE_ARRAY
                );
            });
        }

        return $this;
    }

    public function getControlIdsArray()
    {
        return array_merge(Constants::COST_CENTER_CONTROL_ID_ARRAY, [
            $this->findByCode(4)->code
        ]);
    }

    public function transform($data)
    {
        $this->addTransformationPlugin(function ($costCenter) {
            $costCenter[
                'name'
            ] = "{$costCenter['code']} - {$costCenter['name']}";

            return $costCenter;
        });

        return parent::transform($data);
    }

    public function getTransportAndCreditIdsArray()
    {
        return Cache::remember(
            'getTransportAndCreditIdsArray',
            60,
            function () {
                return [
                    $this->findByCode(1)->id,
                    $this->findByCode(2)->id,
                    $this->findByCode(3)->id
                ];
            }
        );
    }

    public function costCenterLimitsTable()
    {
        return Cache::remember('cost-center-limits-table', 180, function () {
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
                'XI' => 'Tarifas'
            ];

            $allResponse = collect();

            $i = 1;
            $roman = 'I';
            while (
                $parent = CostCenter::where(
                    'code',
                    $roman = NumConvert::roman($i)
                )->first()
            ) {
                if ($i == 6) {
                    $costCenters = CostCenter::where(
                        'parent_code',
                        $roman
                    )->get();

                    $costCenters->each(function ($costCenter) use (
                        $abbreviations,
                        $allResponse,
                        $roman,
                        $i,
                        $parent
                    ) {
                        $costCenterArrayResponse = [
                            'abbreviation' =>
                                $abbreviations[$costCenter->code] ?? '',
                            'number' => $i,
                            'limit' => $costCenter->limit ?? null,
                            'roman' => $costCenter->code,
                            'ids' => collect($costCenter->id)
                        ];

                        $allResponse->push($costCenterArrayResponse);
                    });
                } else {
                    $limit = 0;

                    $costCenterIds = CostCenter::where('code', $roman)
                        ->orWhere('parent_code', $roman)
                        ->get()
                        ->each(function ($item) use (&$limit) {
                            $limit += $item->limit ?? 0;
                        })
                        ->map(function ($item) {
                            return $item->id;
                        });

                    $costCenterIds = $collection = collect($costCenterIds);

                    $costCenterArrayResponse = [
                        'abbreviation' => $abbreviations[$roman] ?? '',
                        'number' => $i,
                        'limit' => $limit == 0 ? null : $limit,
                        'roman' => $roman,
                        'ids' => $collection
                    ];

                    $allResponse->push($costCenterArrayResponse);
                }

                $i++;
            }

            return $allResponse;
        });
    }
}
