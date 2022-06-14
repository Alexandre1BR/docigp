<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CostCenterStore as CostCenterRequest;
use App\Data\Repositories\CostCenters as CostCentersRepository;
use App\Http\Requests\CostCenterUpdate as CostCenterUpdateRequest;
use App\Support\Constants;

class CostCenters extends Controller
{
    public function index()
    {
        return $this->view('admin.cost_centers.index')->with(
            'costCenters',
            app(CostCentersRepository::class)
                ->disablePagination()
                ->all()
        );
    }

    public function create()
    {
        formMode(Constants::FORM_MODE_CREATE);

        return $this->view('admin.cost_centers.form')->with([
            'costCenter' => app(CostCentersRepository::class)->new(),
        ]);
    }

    public function store(CostCenterRequest $request)
    {
        app(CostCentersRepository::class)->create($request->all());

        return redirect()->route('cost-centers.index');
    }

    public function show($id)
    {
        return $this->view('admin.cost_centers.form')->with([
            'costCenter' => app(CostCentersRepository::class)->findById($id),
        ]);
    }

    /**
     * @param CostCenterUpdateRequest $request
     * @param $id
     * @return mixed
     */
    public function update(CostCenterUpdateRequest $request, $id)
    {
        app(CostCentersRepository::class)->update($id, $request->all());

        return redirect()->route('cost-centers.index');
    }
}
