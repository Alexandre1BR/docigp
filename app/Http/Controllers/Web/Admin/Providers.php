<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\Entry;
use App\Support\Constants;
use App\Http\Controllers\Controller;
use App\Data\Repositories\Providers as ProvidersRepository;
use App\Http\Requests\ProviderStore as ProviderStoreRequest;
use App\Http\Requests\ProviderUpdate as ProviderUpdateRequest;
use App\Data\Repositories\Entries as EntriesRepository;
use Livewire\Component;

class Providers extends Controller
{
    public function index()
    {
        return $this->view('admin.providers.index')->with(
            'providers',
            app(ProvidersRepository::class)
                ->disablePagination()
                ->all()
        );
    }

    public function create()
    {
        formMode(Constants::FORM_MODE_CREATE);
        return $this->view('admin.providers.form')->with([
            'provider' => app(ProvidersRepository::class)->new(),
        ]);
    }

    public function store(ProviderStoreRequest $request)
    {
        app(ProvidersRepository::class)->create($request->all());

        session()->flash('message', 'Gravado com sucesso');

        return redirect()->route('providers.index');
    }

    /**
     * @param ProviderUpdateRequest $request
     * @param $id
     * @return mixed
     */
    public function update(ProviderUpdateRequest $request, $id)
    {
        app(ProvidersRepository::class)->update($id, $request->all());

        session()->flash('message', 'Gravado com sucesso');

        return redirect()->route('providers.index');
    }
}
