<?php

namespace App\Http\Controllers;

use App\Models\{BarangKeluar, Barang, Laporan, Proyek};
use Illuminate\Http\Request;
use App\Http\Requests\BarangKeluarRequest;
use Carbon\Carbon;

use function GuzzleHttp\json_decode;

class BarangKeluarController extends Controller
{
    private $database;
    function __construct()
    {
        $this->middleware('permission:barang-keluar', [
            'only' => ['index', 'store', 'info', 'update', 'destroy']
        ]);
        $this->database = \App\Services\FirebaseService::connect();
    }

    public function index(BarangKeluar $barang_keluar)
    {
        $barang = Barang::select('nama', 'id')->get();
        $proyek = Proyek::select('nama_proyek', 'id')->get();
        $data = $barang_keluar->with('barang', 'proyek')->latest()->get();
        return view('admin.barang-keluar.index', compact('data', 'barang', 'proyek'));
    }

    public function store(BarangKeluar $barang_keluar, BarangKeluarRequest $request)
    {

        // UNCOMMENT KODE DIBAWAH JIKA SUDAH DIKOMPILASI
        if (Barang::find($request->barang_id)->jumlah < $request->jumlah) {
            $title = "Pengeluaran barang dari proyek " . Proyek::find($request->proyek_id)->nama_proyek;
            $this->database
                ->getReference('notication/proyek/' . $request->id)
                ->set([
                    'id' => $request->id,
                    'title' => $title,
                    'isRead' => 'no',
                    'created_at' => time()
                ]);
            return back()->with('error', 'Stok barang tidak mencukupi');
        } else {
            if (date('Y-m-d', strtotime(Proyek::find($request->proyek_id)->created_at)) >= $request->tgl_brg_keluar) {
                $result = $barang_keluar->create($request->all());
                $id = $result->id;
                $title = "Pengeluaran barang dari proyek " . Proyek::find($result->proyek_id)->nama_proyek;
                Barang::find($request->barang_id)->decrement('jumlah', $request->jumlah);
                $this->database
                    ->getReference('notication/proyek/' . $id)
                    ->set([
                        'id' => $id,
                        'title' => $title,
                        'isRead' => 'no',
                        'nama_barang' => Barang::find($result->barang_id)->nama,
                        'jumlah' => $result->jumlah,
                        'created_at' => time()
                    ]);
                return back()->with('success', 'Stok berhasil dikurangi');
            } else {
                // dd('test');
                return back()->with('error', 'Tanggal pengeluaran barang tidak boleh lebih kecil dari tanggal penginputan barang');
            }
        }


        // untuk laporan
        // Laporan::create([
        //     'nama' => $result->barang->nama,
        //     'orang' => $result->proyek->nama_proyek,
        //     'jumlah' => $request->jumlah,
        //     // 'berat' => $request->berat,
        //     // 'harga' => $request->harga,
        //     'jenis' => 'Barang Keluar',
        //     'root_id' => $result->id

        // ]);
    }

    public function info(BarangKeluar $barang_keluar)
    {
        $data = $barang_keluar->with('barang', 'proyek')->find(request('id'));

        return $data;
    }

    public function getBarangProyek(Request $request)
    {
        $data = Proyek::find($request->id);
        $barang = json_decode($data->barang_id);
        $jumlah = json_decode($data->jumlah);
        $listBarang = [];
        foreach ($barang as $key => $value) {
            $listBarang[$key]['id'] = Barang::find($value)->id;
            $listBarang[$key]['nama'] = Barang::find($value)->nama;
            $listBarang[$key]['keterangan'] = BarangKeluar::where('proyek_id', $request->id)->where('barang_id', $value)->sum('jumlah') != 0 ? 'Barang sudah diambil' : 'Barang belum diambil';
            $listBarang[$key]['jumlah'] = $jumlah[$key];
        }
        return $listBarang;
    }

    public function getDetailBarangProyek($id)
    {
        $data = Barang::find($id);
        return $data;
    }

    public function update(BarangKeluar $barang_keluar, BarangKeluarRequest $request)
    {
        $result = $barang_keluar->find($request->id);

        $jumlah =  $result->jumlah - $request->jumlah;

        $result->update($request->all());

        // untuk laporan
        Laporan::where('jenis', 'Barang Keluar')->where('root_id', $result->id)->update([
            'nama' => $result->barang->nama,
            'orang' => $result->proyek->nama_proyek,
            'jumlah' => $request->jumlah,
            // 'berat' => $request->berat,
            'harga' => $request->harga,
            'jenis' => 'Barang Keluar'
        ]);

        // untuk barang
        $result->barang->increment('jumlah', $jumlah);

        return back()->with('success', 'Stok berhasil di update');
    }

    public function destroy(BarangKeluar $barang_keluar, $id)
    {
        $data = $barang_keluar->find($id);
        $data->barang->increment('jumlah', $data->jumlah);
        Laporan::where('jenis', 'Barang Keluar')->where('root_id', $data->id)->delete();
        $data->delete();

        return back();
    }
}
