<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Data\Repositories\Providers as ProvidersRepository;
use App\Http\Requests\ProviderStore as ProviderStoreRequest;
use App\Http\Requests\ProviderUpdate as ProviderUpdateRequest;

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
