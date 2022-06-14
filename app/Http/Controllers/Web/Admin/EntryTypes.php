<?php

namespace App\Http\Controllers\Web\Admin;

use App\Data\Repositories\Providers as ProvidersRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\EntryType as EntryTypeRequest;
use App\Data\Repositories\EntryTypes as EntryTypesRepository;
use App\Http\Requests\ProviderUpdate as ProviderUpdateRequest;
use App\Support\Constants;

class EntryTypes extends Controller
{
    public function index()
    {
        return $this->view('admin.entry_types.index')->with(
            'entryTypes',
            app(EntryTypesRepository::class)
                ->disablePagination()
                ->all()
        );
    }

    public function create()
    {
        formMode(Constants::FORM_MODE_CREATE);

        return $this->view('admin.entry_types.form')->with([
            'entryType' => app(EntryTypesRepository::class)->new(),
        ]);
    }

    public function store(EntryTypeRequest $request)
    {
        app(EntryTypesRepository::class)->create($request->all());

        return redirect()->route('entry-types.index');
    }

    public function show($id)
    {
        return $this->view('admin.entry_types.form')->with([
            'entryType' => app(EntryTypesRepository::class)->findById($id),
        ]);
    }

    /**
     * @param EntryTypeRequest $request
     * @param $id
     * @return mixed
     */
    public function update(EntryTypeRequest $request, $id)
    {
        app(EntryTypesRepository::class)->update($id, $request->all());

        return redirect()->route('entry-types.index');
    }
}
