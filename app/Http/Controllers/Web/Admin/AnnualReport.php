<?php

namespace App\Http\Controllers\Web\Admin;

use App\Data\Models\Congressman;
use App\Http\Controllers\Controller;
use App\Services\PDF\Service as PDF;
use Chumper\Zipper\Zipper;
use App\Services\AnnualReport\Service as AnnualReportService;
use Illuminate\Support\Facades\Storage;

class AnnualReport extends Controller
{
    public function index()
    {
        $zipPath = public_path(make_filename('report', 'zip'));

        $zipper = app(Zipper::class)->make($zipPath);

        $folderPath = public_path('temp/');

        foreach (Congressman::all() as $congressman) {
            set_time_limit(0);

            app(PDF::class)
                ->initialize(
                    view('admin.annual-report.index')
                        ->with(
                            $parameters = app(
                                AnnualReportService::class
                            )->getMainTable('2019', $congressman)
                        )
                        ->render()
                )
                ->save($folderPath . make_pdf_filename($congressman->name));
        }

        $zipper->add(glob($folderPath));

        $zipper->close();

        if (\File::exists($folderPath)) {
            \File::deleteDirectory($folderPath);
        }
        Storage::delete($zipPath);

        return response()->download($zipPath);
    }
}
