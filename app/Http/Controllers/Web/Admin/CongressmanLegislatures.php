<?php

namespace App\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Data\Repositories\Congressmen as CongressmenRepository;
use App\Data\Repositories\CongressmanLegislatures as CongressmanLegislaturesRepository;

class CongressmanLegislatures extends Controller
{
    /**
     * @var CongressmenRepository
     */
    private $congressmanLegislaturesRepository;

    /**
     * Users constructor.
     *
     * @param CongressmenRepository $congressmenRepository
     */
    public function __construct(
        CongressmanLegislaturesRepository $congressmanLegislaturesRepository
    ) {
        $this->congressmanLegislaturesRepository = $congressmanLegislaturesRepository;
    }

    public function editLegislature(Request $request)
    {
        $this->congressmanLegislaturesRepository
            ->update($request['congressmanLegislature_id'], $request->except('token'));

        return redirect()
            ->route('congressmen.show',$request['congressman_id'])
            ->with(
                $this->getSuccessMessage('Atualizado com Sucesso')
            );
    }

    public function includeInLegislature(Request $request)
    {

        $this->congressmanLegislaturesRepository->includeInLegislature(
            $request['legislature_id'],
            $request['congressman_id'],
            $request['started_at']
        );

        return redirect()
            ->route('congressmen.index')
            ->with(
                $this->getSuccessMessage('Removido da Legislatura com Sucesso')
            );
    }
}
