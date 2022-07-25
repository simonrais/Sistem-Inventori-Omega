<?php

namespace App\Http\Controllers;

use App\Models\{Barang, Gudang, Kategori};
use Illuminate\Http\Request;
use App\Http\Requests\BarangRequest;
use Illuminate\Support\Facades\DB;

// use DB;

class BarangController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:barang', [
            'only' => ['index', 'store', 'info', 'update', 'destroy']
        ]);
    }

    public function index(Barang $barang)
    {
        $gudang = Gudang::all();
        $kategori = Kategori::all();

        $data = null;

        if (request('filter_gudang')) {
            $data = $barang->with('kategori')->where('gudang_id', request('filter_gudang'))->get();
        } else {
            $data = $barang->with('kategori')->get();
        }

        $q = DB::table('barangs')->select(DB::raw('MAX(RIGHT(kode, 4)) as kod'));
        $kd = "";
        if ($q->count() > 0) {
            foreach ($q->get() as $k) {
                $tmp = ((int)$k->kod) + 1;
                $kd = sprintf("%04s", $tmp);
            }
        } else {
            $kd = "0001";
        }

        // return $data->kategori->nama;

        return view('admin.barang.index', compact('data', 'gudang', 'kategori', 'kd'));
    }

    // KODE TAMBAHAN
    public function getAllBarang()
    {
        $data = Barang::all();
        return response()->json($data);
    }

    public function store(Barang $barang, Gudang $gudang, BarangRequest $request)
    {


        $payload = $request->all();

        $request->validate(
            [
                'file' => 'required|image|max:2048'
            ],
            [
                'file.image' => 'File harus berupa gambar!
        Silahkan Upload kembali'
            ]
        );

        if ($request->hasFile('file')) {
            $fileName = time() . '.' . $request->file->extension();

            $request->file->move(public_path('uploads/barang'), $fileName);
            $payload['image'] = $request->file ? $fileName : null;
        }

        $barang->create($payload);

        return back()->with('success', 'barang berhasil ditambahkan');
    }

    public function info(Barang $barang)
    {
        $data = $barang->with('kategori')->find(request('id'));
        $gudang = Gudang::find($data->gudang_id);
        return [
            'barang' => $data,
            'gudang' => [
                'id' => $gudang->id,
                'nama' => $gudang->nama
            ]
        ];
    }

    public function update(Barang $barang, BarangRequest $request)
    {
        if ($request->hasFile('file')) {
            $fileName = time() . '.' . $request->file->extension();

            $request->file->move(public_path('uploads/barang/'), $fileName);
        }

        $result = $barang->find($request->id);

        $result->update($request->except('file'));

        if ($request->hasFile('file')) {
            $result->update([
                'image' => $fileName,
            ]);
        }


        return back()->with('success', 'Barang berhasil diupdate');
    }

    public function destroy(Barang $barang, $id)
    {
        $data = $barang->find($id);
        $data->delete();

        return back();
    }
}
