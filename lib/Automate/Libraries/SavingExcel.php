<?php

/*
 * PENDATAAN MAARIF NU
 * Dikembangkan oleh Rohmad Eko Wahyudi
 * rohmad.ew@gmail.com
 */

namespace App\Libraries;

use Illuminate\Http\Request;
use App\Model\Pengaturan;
use Illuminate\Support\Facades\Session;

/**
 * Description of SavingExcel
 *
 * @author Rohmad Eko Wahyudi
 */
class SavingExcel {

    //put your code here

    public static function proccess(Request $request, $class, $routeFailed) {
        if ($request->hasFile('files')) {
            $uploaded_files = $request->file('files');
            $extension = strtolower($uploaded_files->getClientOriginalExtension());

            if (!($extension == 'xls' || $extension == 'xlsx')) {
                Session::flash("flash_notification", [
                    "level" => "danger",
                    "title" => "Gagal",
                    "message" => "File gagal diupload. File harus xls atau xlsx."
                ]);

                return redirect()->route($routeFailed);
            }

            $versiOpen = md5(Pengaturan::where('kode', 'versi_format_excel')->first()->isi);

            if ($versiOpen == md5('1')) {
                return true;
            } else {
                Session::flash("flash_notification", [
                    "level" => "danger",
                    "title" => "Gagal",
                    "message" => "File gagal diupload. Sistem belum siap untuk digunakan."
                ]);

                return redirect()->route($routeFailed);
            }
        }

        return null;
    }

}
