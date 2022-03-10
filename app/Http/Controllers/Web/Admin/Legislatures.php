<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Legislature as LegislatureRequest;
use App\Data\Repositories\Legislatures as LegislaturesRepository;
use App\Data\Repositories\CongressmanLegislatures as CongressmanLegislaturesRepository;
use App\Support\Constants;

class Legislatures extends Controller
{
    public function index()
    {
        return $this->view('admin.legislatures.index')->with(
            'legislatures',
            app(LegislaturesRepository::class)->all()
        );
    }

    public function create()
    {
        formMode(Constants::FORM_MODE_CREATE);

        return $this->view('admin.legislatures.form')->with([
            'legislature' => app(LegislaturesRepository::class)->new(),
        ]);
    }

    public function store(LegislatureRequest $request)
    {
        app(LegislaturesRepository::class)->create($request->all());
        return redirect()->route('legislatures.index');
    }

    public function show($id)
    {
        return $this->view('admin.legislatures.form')
            ->with([
                'legislature' => app(LegislaturesRepository::class)->findById($id),
            ])
            ->with([
                'congressmanLegislatures' => app(
                    CongressmanLegislaturesRepository::class
                )->filterByLegislatureId($id),
            ]);
    }

    /**
     * @param LegislatureRequest $request
     * @param $id
     * @return mixed
     */
    public function update(LegislatureRequest $request, $id)
    {
        app(LegislaturesRepository::class)->update($id, $request->all());
        return redirect()->route('legislatures.index');
    }
}
