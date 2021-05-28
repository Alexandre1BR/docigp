<?php

namespace App\Http\Controllers\Web\Admin;

use App\Data\Models\Congressman;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnnualReportWithCongressman as RequestsAnnualReportWithCongressman;
use App\Http\Requests\AnnualReportWithoutCongressman as RequestsAnnualReportWithoutCongressman;
use App\Services\AnnualReport\General\Service as AnnualReportGeneralService;
use App\Services\PDF\Service as PDF;
use App\Services\AnnualReport\Congressman\Service as AnnualReportCongressmanService;
use Illuminate\Http\Request;

class AnnualReport extends Controller
{
    public function index()
    {
        return view('admin.annual-reports.index')->with([
            'congressmen' => Congressman::withoutGlobalScopes()
                ->orderBy('name', 'asc')
                ->get()
        ]);
    }

    public function generate(RequestsAnnualReportWithCongressman $request)
    {
        set_time_limit(0);
        $year = $request->get('year');
        $congressman = Congressman::withoutGlobalScopes()->find(
            $request->get('congressman_id')
        );

        return app(PDF::class)
            ->initialize(
                view('admin.annual-reports.pdf')
                    ->with(
                        $parameters = app(
                            AnnualReportCongressmanService::class
                        )->getMainTable($year, $congressman)
                    )
                    ->with(
                        'logoBlob',
                        base64_encode(
                            file_get_contents(public_path('img/logo-alerj.png'))
                        )
                    )
                    ->render(),
                'A4',
                'landscape'
            )
            ->download(make_pdf_filename($congressman->name));
    }

    public function generateGeneral(RequestsAnnualReportWithoutCongressman $request)
    {
        $year = $request->get('year');

//        return view('admin.annual-reports.general')->with(
//            'logoBlob',
//            base64_encode(
//                file_get_contents(public_path('img/logo-alerj.png'))
//            )
//        )->with('year',$year);
        return app(PDF::class)
            ->initialize(
                view('admin.annual-reports.general')->with(
                    'logoBlob',
                    base64_encode(
                        file_get_contents(public_path('img/logo-alerj.png'))
                    )
                )->with(
                    $parameters = app(
                        AnnualReportGeneralService::class
                    )->getMainTable($year)
                )->with('year', $year)
                    ->render(),
                'A4',
                'landscape'
            )->download(make_pdf_filename('Relatorio Anual de Todos os Centro de Custo'));
    }
}
