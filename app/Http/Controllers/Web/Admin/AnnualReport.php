<?php

namespace App\Http\Controllers\Web\Admin;

use App\Data\Models\Congressman;
use App\Http\Controllers\Controller;
use App\Services\PDF\Service as PDF;
use App\Services\AnnualReport\Service as AnnualReportService;
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

    public function generate(Request $request)
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
                            AnnualReportService::class
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
}
