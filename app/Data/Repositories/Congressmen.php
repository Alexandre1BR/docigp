<?php

namespace App\Data\Repositories;

use App\Models\Congressman;
use App\Models\ChangeUnread;
use App\Models\CongressmanLegislature;
use PragmaRX\Coollection\Package\Coollection;
use App\Data\Repositories\Departments as DepartmentsRepository;

class Congressmen extends Repository
{
    /**
     * @var string
     */
    protected $model = Congressman::class;

    private function canHaveMandate($congressman)
    {
        return !collect([
            'Luis Martins',
            'Chiquinho da Mangueira',
            'Marcus Vinicius Neskau',
            'André Correa',
            'Marcos Abrahão'
        ])->contains($congressman->name);
    }

    private function createCongressmanFromRemote($congressman, $departmentId)
    {
        if (is_null($this->findParty($congressman['SiglaPartido']))) {
            dump(
                'O partido ' .
                    $congressman['SiglaPartido'] .
                    ' não consta no banco de dados'
            );
        }
        return $this->firstOrCreate(
            [
                'remote_id' => $congressman['ID']
            ],
            [
                'name' => ($name = $this->normalizeName($congressman['Nome'])),

                'nickname' =>
                    $this->normalizeName($congressman['NomePolitico']) ?? $name,

                'party_id' => $this->findParty($congressman['SiglaPartido'])
                    ->id,

                'photo_url' => $congressman['Foto'],

                'department_id' => $departmentId,

                'thumbnail_url' => $congressman['FotoPequena']
            ]
        );
    }

    private function findParty($party)
    {
        return app(Parties::class)->findByCode(
            $this->normalizePartyCode($party)
        );
    }

    /**
     * @param $party
     * @return string
     */
    private function normalizePartyCode($party)
    {
        switch ($party) {
            case 'Novo':
                return 'NOVO';
            case 'PC do B':
                return 'PCdoB';
            case 'Patriota':
                return 'PATRI';
            case 'Avante':
                return 'AVANTE';
        }

        return $party;
    }

    /**
     * @param $data
     */
    public function sync(Coollection $data)
    {
        $this->withGlobalScopesDisabled(function () use ($data) {
            $data->each(function ($congressman) {
                $department = app(
                    DepartmentsRepository::class
                )->createDepartmentFromCongressman($congressman);

                $congressman = $this->createCongressmanFromRemote(
                    $congressman,
                    $department->id
                );

                if (
                    $congressman->wasRecentlyCreated &&
                    $this->canHaveMandate($congressman)
                ) {
                    $legislature = CongressmanLegislature::firstOrCreate(
                        [
                            'congressman_id' => $congressman->id,
                            'legislature_id' => app(
                                Legislatures::class
                            )->getCurrent()->id
                        ],
                        ['started_at' => now()]
                    );

                    $congressman->legislatures()->save($legislature);
                }
            });
        });
    }

    public function withGlobalScopesDisabled($callable)
    {
        Congressman::disableGlobalScopes();

        $callable();

        Congressman::enableGlobalScopes();
    }

    protected function filterCheckboxes($query, array $filter)
    {
        if (isset($filter['withMandate'])) {
            $query->active();
        }

        if (isset($filter['withoutMandate'])) {
            $query->nonActive();
        }

        if (isset($filter['withPendency'])) {
            $query->withPendency();
        }

        if (isset($filter['withoutPendency'])) {
            $query->withoutPendency();
        }

        if (isset($filter['unread'])) {
            $query->unread();
        }

        // Vagner Victer mandou mostrar apenas os que aderiram
        // Na visão do cidadão
        if (!auth()->user()) {
            $query->joined();
        }

        if (isset($filter['joined'])) {
            $query->joined();
        }

        if (isset($filter['notJoined'])) {
            $query->notJoined();
        }
    }

    public function searchFromRequest($search = null)
    {
        $search = is_null($search)
            ? collect()
            : collect(explode(' ', $search))->map(function ($item) {
                return strtolower($item);
            });

        $columns = collect(['number' => 'string']);

        $query = $this->model::query();

        $search->each(function ($item) use ($columns, $query) {
            $columns->each(function ($type, $column) use ($query, $item) {
                if ($type === 'string') {
                    $query->orWhere(
                        DB::raw("lower({$column})"),
                        'like',
                        '%' . $item . '%'
                    );
                } else {
                    if ($this->isDate($item)) {
                        $query->orWhere($column, '=', $item);
                    }
                }
            });
        });

        return $this->makeResultForSelect($query->orderBy('name')->get());
    }

    public function associateWithUser($request)
    {
        return app(Users::class)->associateCongressmanWithUser(
            $request['id'],
            $request
        );
    }

    public function markAsRead($id)
    {
        if (auth()->user()) {
            if (
                ChangeUnread::where('congressman_id', $id)
                    ->where('user_id', auth()->user()->id)
                    ->delete()
            ) {
                $this->fireEvents(Congressman::find($id), 'Updated');
            }
        }
    }
}
