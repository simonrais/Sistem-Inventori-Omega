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
        // $this->database = \App\Services\FirebaseService::connect();
    }

    public function index(Proyek $proyek)
    {
        $barangs = Barang::get();
        $proyeks = $proyek->select('nama_proyek')->groupBy('nama_proyek')->get();
        $jenisBarang = [];
        $data = null;

        if (request('filter_proyek')) {
            $data = $proyek->where('nama_proyek', request('filter_proyek'))->get();
        } else {
            $data = $proyek->with('barang')->get();
        }


        // FIXED
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
            // dd($payload[$key]);
            foreach ($payload[$key]['barangs'] as $keyi => $value) {
                $payload[$key]['barangs'][$i] = Barang::find($value);
                $i++;
            }
        }

        return view('admin.proyek.index', compact('data', 'barangs', 'proyeks'), ['payload' => $payload]);
    }

    public function store(Proyek $proyek, Request $request)
    {

        $user = Auth::user();
        $payload = $request->all();
        $payload['user_id'] = $user->id;
        $payload['barang_id'] = json_encode($request['barang_id']);
        $payload['jumlah'] = json_encode($request['jumlah']);
        $result = $proyek->create($payload);
        // return $payload;

        $jenisBarang = json_decode($result['barang_id'], true);
        foreach ($jenisBarang as $key => $value) {
            # code...
            $barang[$key] = Barang::find($value);
        }
        // }
        $jml = count($barang);
        $namaBarang = '';
        foreach ($barang as $key => $value) {
            // dd($value);
            // return $value->nama;
            $namaBarang .=  ' ' . $value->nama . ', ';
        }

        $id = $result->id;
        $title = "Penambahan kebutuhan barang {$namaBarang} dari proyek {$result->nama_proyek} ({$user->name})";
        // $this->database
        //     ->getReference('notication/proyek/' . $id)
        //     ->set([
        //         'user' => $user->name,
        //         'id' => $id,
        //         'title' => $title,
        //         'isRead' => 'no',
        //         'nama_barang' => $barang->nama,
        //         'jumlah' => $result->jumlah,
        //         'created_at' => time()
        //     ]);

        return back()->with('success', 'Data berhasil ditambahkan');
    }

    public function info(Proyek $proyek)
    {
        $data = $proyek->find(request('id'));
        // dd($data);
        $barang_id = json_decode($data->barang_id, true);
        $barang = [];
        $payload = [];
        $i = 0;
        foreach ($barang_id as $value) {
            $barang[$i] = Barang::find($value);
            $i++;
        }
        // return $barang;
        $payload['nama_proyek'] = $data->nama_proyek;
        $payload['barang'] = $barang;
        $payload['jumlah'] = json_decode($data->jumlah, true);
        $payload['id'] = $data->id;
        return $payload;
        // return [
        //     'proyek' => $data,
        //     'barang' => [
        //         'id' => $barang->id,
        //         'nama' => $barang->nama
        //     ]
        // ];
    }


    public function update(Proyek $proyek, ProyekRequest $request)
    {
        $proyek->find($request->id)->update($request->all());

        return back();
    }

    public function destroy(Proyek $proyek, $id)
    {
        $data = $proyek->find($id);
        $data->delete();

        return back();
    }
}
