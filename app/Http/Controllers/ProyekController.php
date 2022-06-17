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
        $this->database = \App\Services\FirebaseService::connect();
    }

    public function index(Proyek $proyek)
    {
        $barangs = Barang::get();
        $proyeks = $proyek->select('nama_proyek')->groupBy('nama_proyek')->get();

        $data = null;

        if (request('filter_proyek')) {
            $data = $proyek->where('nama_proyek', request('filter_proyek'))->get();
        } else {
            $data = $proyek->with('barang')->get();
        }

        return view('admin.proyek.index', compact('data', 'barangs', 'proyeks'));
    }

    public function store(Proyek $proyek, ProyekRequest $request)
    {
        $user = Auth::user();
        $payload = $request->all();
        $payload['user_id'] = $user->id;
        $result = $proyek->create($payload);

        Barang::find($result->barang_id)->decrement('jumlah', $request->jumlah);
        $barang = Barang::find($result->barang_id);

        $id = $result->id;
        $title = "Penambahan kebutuhan barang {$barang->nama} dari proyek {$result->nama_proyek} ({$user->name})";
        $this->database
            ->getReference('notication/proyek/' . $id)
            ->set([
                'user' => $user->name,
                'title' => $title,
                'nama_barang' => $barang->nama,
                'jumlah' => $result->jumlah,
                'sisa' => $barang->jumlah,
                'created_at' => time()
            ]);

        return back()->with('success', 'Data berhasil ditambahkan');
    }

    public function info(Proyek $proyek)
    {
        $data = $proyek->find(request('id'));
        $barang = Barang::find($data->id);
        return [
            'proyek' => $data,
            'barang' => [
                'id' => $barang->id,
                'nama' => $barang->nama
            ]
        ];
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
