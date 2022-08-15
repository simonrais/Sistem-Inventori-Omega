<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProyekRequest;
use App\Models\Barang;
use App\Models\Proyek;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProyekController extends Controller
{
    private $database;

    function __construct()
    {
        $this->middleware('permission:proyek', [
            'only' => ['index', 'store', 'info', 'update', 'destroy']
        ]);
        // UNCOMMENT KODE DIBAWAH
        $this->database = \App\Services\FirebaseService::connect();
    }

    public function index(Proyek $proyek)
    {
        $barangs = Barang::get();
        $proyeks = $proyek->with('user')->select('nama_proyek')->groupBy('nama_proyek')->get();
        $jenisBarang = [];
        $data = null;

        if (request('filter_proyek')) {
            $data = $proyek->where('nama_proyek', request('filter_proyek'))->get();
        } else {
            $data = $proyek->with('barang')->get();
        }

        $payload = [];
        // KODE TAMBAHAN
        foreach ($data as $key => $value) {
            $i = 0;
            $payload[$key]['nama_proyek'] = $value->nama_proyek;
            $payload[$key]['barangs'] = json_decode($value->barang_id, true);
            $payload[$key]['jumlah'] = json_decode($value->jumlah, true);
            $payload[$key]['id'] = $value->id;
            $payload[$key]['created_at'] = $value->created_at;
            $payload[$key]['updated_at'] = $value->updated_at;
            $payload[$key]['user_id'] = $value->user_id;
            $payload[$key]['user'] = $value->user->name;

            foreach ($payload[$key]['barangs'] as $keyi => $value) {
                $payload[$key]['barangs'][$i] = Barang::find($value);
                $i++;
            }
        }

        // return $payload;
        return view('admin.proyek.index', compact('data', 'barangs', 'proyeks'), ['payload' => $payload]);
    }

    public function indexEstimator(Proyek $proyek)
    {
        $barangs = Barang::get();
        $proyeks = $proyek->with('user')->select('nama_proyek')->groupBy('nama_proyek')->get();
        $jenisBarang = [];
        $data = null;

        if (request('filter_proyek')) {
            $data = $proyek->where('nama_proyek', request('filter_proyek'))->get();
        } else {
            $data = $proyek->with('barang')->get();
        }

        $payload = [];
        // KODE TAMBAHAN
        foreach ($data as $key => $value) {
            $i = 0;
            $payload[$key]['nama_proyek'] = $value->nama_proyek;
            $payload[$key]['barangs'] = json_decode($value->barang_id, true);
            $payload[$key]['jumlah'] = json_decode($value->jumlah, true);
            $payload[$key]['id'] = $value->id;
            $payload[$key]['created_at'] = $value->created_at;
            $payload[$key]['updated_at'] = $value->updated_at;
            $payload[$key]['user_id'] = $value->user_id;
            $payload[$key]['user'] = $value->user->name;

            foreach ($payload[$key]['barangs'] as $keyi => $value) {
                $payload[$key]['barangs'][$i] = Barang::find($value);
                $i++;
            }
        }

        // return $payload;
        return view('admin.laporan.estimator', compact('data', 'barangs', 'proyeks'), ['payload' => $payload]);
    }

    public function create()
    {
        $barangs = Barang::get();
        return view('admin.proyek.add-proyek', compact('barangs'));
    }

    public function store(Proyek $proyek, Request $request)
    {
        $barang_id = [];
        $jumlah = [];
        foreach ($request['barang_id'] as $key => $value) {
            if ($value != null) {
                $barang_id[$key] = $value;
            }
        }
        foreach ($request['jumlah'] as $key => $value) {
            if ($value != null) {
                $jumlah[$key] = $value;
            }
        }
        // return $test;
        $user = Auth::user();
        $payload = $request->all();
        $payload['user_id'] = $user->id;
        $payload['barang_id'] = json_encode($barang_id);
        $payload['jumlah'] = json_encode($jumlah);
        $result = $proyek->create($payload);

        $jenisBarang = json_decode($result['barang_id'], true);
        foreach ($jenisBarang as $key => $value) {
            $barang[$key] = Barang::find($value);
        }

        $jml = count($barang);
        $namaBarang = '';
        foreach ($barang as $key => $value) {
            $namaBarang .=  ' ' . $value->nama . ', ';
        }

        $id = $result->id;
        $title = "Penambahan kebutuhan barang dari proyek {$result->nama_proyek} ({$user->name})";
        // UNCOMMENT KODE DIBAWAH JIKA SUDAH DIKOMPILASI
        $this->database
            ->getReference('notication/proyek/' . $id)
            ->set([
                'user' => $user->name,
                'id' => $id,
                'title' => $title,
                'isRead' => 'no',
                'nama_barang' => $namaBarang,
                'jumlah' => $result->jumlah,
                'created_at' => time()
            ]);

        return redirect()->route('admin.proyek.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function info(Proyek $proyek)
    {
        // KODE TAMBAHAN
        $data = $proyek->find(request('id'));
        $barang_id = json_decode($data->barang_id, true);
        $barang = [];
        $payload = [];
        $i = 0;
        foreach ($barang_id as $value) {
            $barang[$i] = Barang::find($value);
            $i++;
        }

        $payload['nama_proyek'] = $data->nama_proyek;
        $payload['barang'] = $barang;
        $payload['jumlah'] = json_decode($data->jumlah, true);
        $payload['id'] = $data->id;
        return $payload;
    }

    // KODE TAMBAHAN
    public function update(Proyek $proyek, Request $request)
    {
        $payload = $request->all();
        $payload['barang_id'] = json_encode($request['barang_id']);
        $payload['jumlah'] = json_encode($request['jumlah']);
        // dd($request);
        $proyek->find($payload['id'])->update($payload);

        return back()->with('success', 'Data berhasil diupdate');
    }

    public function destroy(Proyek $proyek, $id)
    {
        $data = $proyek->find($id);
        $data->delete();

        return back();
    }
}
