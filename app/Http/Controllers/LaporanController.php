<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Laporan $laporan, Request $request)
    {
        // return  $request;
        $kategori = $request->kategori;
        if ($request['tanggal'] != null) {
            if (date("Y-m-d", strtotime(explode('-', $request->tanggal)[0])) == date("Y-m-d", strtotime(explode('-', $request->tanggal)[1]))) {
                $request['tanggal'] = null;
            }
        }
        // return $request == ''  ? 'true' : 'false';
        if ($request['tanggal'] != null && $request['kategori'] == null) {
            $data = DB::select("SELECT * FROM `laporans` WHERE `created_at` BETWEEN '" . date("Y-m-d", strtotime(explode('-', $request->tanggal)[0])) . "' AND '" . date("Y-m-d", strtotime(explode('-', $request->tanggal)[1])) . "'");
        } else if ($request['kategori'] != null && $request['tanggal'] == null) {
            // return true;
            if ($request['kategori'] == 'masuk' || $request['kategori'] == 'semua') {
                $data = BarangMasuk::with('barang', 'supplier')->get();
            } else {
                $data = BarangKeluar::with('barang', 'proyek')->get();
            }
            // $data = DB::select("SELECT * FROM `laporans` WHERE `jenis` = '" . $request->kategori . "'");
        } else if ($request['tanggal'] != null && $request['kategori'] != null) {
            // $data = DB::select("SELECT * FROM `laporans` WHERE `jenis` = '" . $request->kategori . "' AND `created_at` BETWEEN '" . date("Y-m-d", strtotime(explode('-', $request->tanggal)[0])) . "' AND '" . date("Y-m-d", strtotime(explode('-', $request->tanggal)[1])) . "'");
            if ($request['kategori'] == 'masuk' || $request['kategori'] == 'semua') {
                $data = BarangMasuk::with('barang', 'supplier')->whereBetween('tgl_brg_masuk', [date("Y-m-d", strtotime(explode('-', $request->tanggal)[0])), date("Y-m-d", strtotime(explode('-', $request->tanggal)[1]))])->get();
            } else {
                $data = BarangKeluar::with('barang', 'proyek')->whereBetween('tgl_brg_keluar', [date("Y-m-d", strtotime(explode('-', $request->tanggal)[0])), date("Y-m-d", strtotime(explode('-', $request->tanggal)[1]))])->get();
            }
        } else {
            $data = BarangMasuk::with('barang', 'supplier')->get();
        }
        // return $data;
        return view('admin.laporan.index', compact('data', 'kategori'));
    }

    // public function filter(Request $laporan)
    // {

    //     return view('admin.laporan.index', compact('data'));
    // }
}
