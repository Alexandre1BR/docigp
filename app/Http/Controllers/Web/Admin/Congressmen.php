<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Legislature;
use Illuminate\Http\Request;
use App\Http\Requests\Congressman as CongressmanRequest;

use App\Data\Repositories\Congressmen as CongressmenRepository;
use App\Data\Repositories\Legislatures as LegislaturesRepository;
use App\Data\Repositories\CongressmanLegislatures as CongressmanLegislaturesRepository;

class Congressmen extends Controller
{
    /**
     * @var CongressmenRepository
     */
    private $congressmenRepository;

    /**
     * Users constructor.
     *
     * @param CongressmenRepository $congressmenRepository
     */
    public function __construct(CongressmenRepository $congressmenRepository)
    {
        $this->congressmenRepository = $congressmenRepository;
    }

    public function create()
    {
        return $this->view('admin.congressmen.form')->with([
            'congressman' => $this->congressmenrepository->new(),
            'parties' => $this->getComboBoxMenus(),
        ]);
    }

    public function store(CongressmanRequest $request)
    {
        $this->congressmenRepository->createFromRequest($request);

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
        $congressman = app(CongressmenRepository::class)->findById($id);
        $congressmanLegislatures = app(
            CongressmanLegislaturesRepository::class
        )->filterByCongressmanId($id);
        $isInCurrentLegislature = app(
            CongressmanLegislaturesRepository::class
        )->isInCurrentLegislature($id);



        $legislatures = Legislature::orderBy('number','desc')->get();

        //TODO selecionar as roles possíveis
        return $this->view('admin.congressmen.form')
            ->with('congressman', $congressman)
            ->with('congressmanLegislatures', $congressmanLegislatures)
            ->with('formDisabled', true)
            ->with('isInCurrentLegislature', $isInCurrentLegislature)
            ->with('legislatures',$legislatures);
    }

    public function associateWithUser(CongressmanRequest $request)
    {
        $this->congressmenRepository->associateWithUser($request);
        return redirect()
            ->route('congressmen.index')
            ->with($this->getSuccessMessage());
    }
}
