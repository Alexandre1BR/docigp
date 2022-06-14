<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CongressmanSettings as CongressmanSettingsRequest;

use App\Data\Repositories\CongressmanSettings as CongressmanSettingsRepository;

class CongressmanSettings extends Controller
{
    /**
     * @var CongressmanSettingsRepository
     */
    private $congressmenRepository;

    /**
     * Users constructor.
     *
     * @param CongressmanSettingsRepository $congressmenRepository
     */
    public function __construct(CongressmanSettingsRepository $congressmenRepository)
    {
        $this->congressmenRepository = $congressmenRepository;
    }

    public function create()
    {
        return $this->view('admin.congressmenSetting.form')->with([
            'congressman' => $this->congressmenrepository->new(),
            'parties' => $this->getComboBoxMenus(),
        ]);
    }

    public function store(CongressmanSettingsRequest $request)
    {
        $this->congressmenrepository->createFromRequest($request);

        return redirect()->route('admin.congressmen.index');
    }

    public function index(Request $request)
    {
        return $this->view('admin.congressmen.index')->with(
            'congressmen',
            $this->congressmenRepository->all()
        );
    }

    public function show($id)
    {
        $congressman = app(CongressmanSettingsRepository::class)->findById($id);

        //TODO selecionar as roles possíveis
        return $this->view('admin.congressman_settings.form')
            ->with('congressman', $congressman)
            ->with('formDisabled', true);
    }
}
