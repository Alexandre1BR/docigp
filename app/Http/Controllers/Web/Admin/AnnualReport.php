<?php

namespace App\Http\Controllers\Web\Admin;

use App\Data\Models\Congressman;
use App\Http\Controllers\Controller;
use App\Services\PDF\Service as PDF;
use Chumper\Zipper\Zipper;
use App\Services\AnnualReport\Service as AnnualReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnualReport extends Controller
{
    public function index()
    {
        return view('admin.annual-reports.index');
    }

    public function generate(Request $request)
    {
        $year = $request->get('year');

        set_time_limit(0);

        $zipFolderPath = public_path('temp-zip/');

        if (\File::exists($zipFolderPath)) {
            \File::deleteDirectory($zipFolderPath);
        }

        $zipPath =
            $zipFolderPath . make_filename('relatorio-anual-' . $year, 'zip');

        $zipper = app(Zipper::class)->make($zipPath);

        $folderPath = public_path('temp-pdf/');

        foreach (Congressman::limit(1)->get() as $congressman) {
            app(PDF::class)
                ->initialize(
                    view('admin.annual-reports.pdf')
                        ->with(
                            $parameters = app(
                                AnnualReportService::class
                            )->getMainTable('2019', $congressman)
                        )
                        ->with(
                            'logoBlob',
                            base64_encode(
                                file_get_contents(
                                    public_path('img/logo-alerj.png')
                                )
                            )
                        )
                        ->render(),
                    'A4',
                    'landscape'
                )
                ->save($folderPath . make_pdf_filename($congressman->name));
        }

        $zipper->add(glob($folderPath));

        $zipper->close();

        if (\File::exists($folderPath)) {
            \File::deleteDirectory($folderPath);
        }

        return response()->download($zipPath);
    }
}
