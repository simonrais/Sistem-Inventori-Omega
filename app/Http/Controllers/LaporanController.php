<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Laporan $laporan, Request $request)
    {

        // return $request == ''  ? 'true' : 'false';
        if ($request['tanggal'] != null) {
            $data = DB::select("SELECT * FROM `laporans` WHERE `created_at` BETWEEN '" . date("Y-m-d", strtotime(explode('-', $request->tanggal)[0])) . "' AND '" . date("Y-m-d", strtotime(explode('-', $request->tanggal)[1])) . "'");
        } else {
            $data = $laporan->latest()->get();
        }
        return view('admin.laporan.index', compact('data'));
    }

    // public function filter(Request $laporan)
    // {

    //     return view('admin.laporan.index', compact('data'));
    // }
}
