<x-app-layout>
    <x-slot name="title">Daftar Kebutuhan Barang Proyek</x-slot>

    @if (session()->has('success'))
        <x-alert type="success" message="{{ session()->get('success') }}" />
    @endif

    <x-card>
        <x-slot name="title">Semua Kebutuhan Barang Proyek</x-slot>
        <x-slot name="option">
            <div>
                <button class="btn btn-primary add"><i class="fas fa-plus"></i> Tambah</button>
            </div>
        </x-slot>
        <div class="table-responsive">
            <table class="display table table-striped table-hover" id="daftar">
                <thead>
                    <tr>
                        <th>Nama Proyek</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Barang</th>
                        <th style="width: 10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <td>{{ $row->nama_proyek }}</td>
                            <td>{{ $row->barang->nama }}</td>
                            <td>{{ $row->jumlah }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary edit" data-id="{{ $row->id }}"><i
                                        class="fas fa-edit"></i></button>
                                <form action="{{ route('admin.proyek.destroy', $row->id) }}"
                                    style="display: inline-block;" method="POST">
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-danger delete"><i
                                            class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </x-card>

    {{-- add model --}}
    <x-modal>
        <x-slot name="title">
            <h6 class="m-0 font-weight-bold text-primary">Tambahkan Kebutuhan Barang</h6>
        </x-slot>
        <x-slot name="id">add</x-slot>


        <form action="{{ route('admin.proyek.store') }}" method="post" class="form-group">
            @csrf
            <div class="form-group">
                <label for="">Nama Proyek</label>
                <input type="text" class="form-control" name="nama_proyek" required="">
            </div>
            <div class="form-group">
                <label for="">Barang</label>
                <select name="barang_id" class="form-control">
                    <option value="">--- Pilih Barang ---</option>
                    @foreach ($barangs as $row)
                        <option value="{{ $row->id }}">{{ $row->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Jumlah Barang</label>
                <input type="number" class="form-control" name="jumlah" required="">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </x-modal>

    {{-- edit model --}}
    <x-modal>
        <x-slot name="title">
            <h6 class="m-0 font-weight-bold text-primary">Edit Barang</h6>
        </x-slot>
        <x-slot name="id">edit</x-slot>


        <form action="{{ route('admin.proyek.update') }}" method="post" id="edit" class="form-group">
            @csrf
            <input type="hidden" name="id" value="">
            <div class="form-group">
                <label for="">Nama Proyek</label>
                <input type="text" class="form-control" name="nama_proyek" required="">
            </div>
            <div class="form-group">
                <label for="">Barang</label>
                <select name="barang_id" class="form-control">
                    <option value="">--- Pilih Barang ---</option>
                    @foreach ($barangs as $row)
                        <option value="{{ $row->id }}">{{ $row->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Jumlah Barang</label>
                <input type="number" class="form-control" name="jumlah">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </x-modal>

    <x-slot name="script">
        <script src="{{ asset('dist/vendor/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('dist/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
        <script>
            $('.add').click(function() {
                $('#add').modal('show')
            })

            $('.edit').click(function() {
                const id = $(this).data('id')

                $.get(`{{ route('admin.proyek.info') }}?id=${id}`, function(data) {
                    $('#edit input[name="id"]').val(id)
                    $('#edit input[name="nama_proyek"]').val(data.proyek.nama_proyek)
                    $('#edit input[name="jumlah"]').val(data.proyek.jumlah)
                    $(`#edit option[value="${data.barang.id}"]`).attr('selected', 'true')
                })

                $('#edit').modal('show')
            })

            $('.delete').click(function(e) {
                e.preventDefault()
                Swal.fire({
                    title: 'Ingin menghapus?',
                    text: 'Data akan dihapus permanen',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).parent().submit()
                    }
                })

            })

            $(document).ready(function() {
                $('#daftar').DataTable();
            });
        </script>
    </x-slot>
</x-app-layout>
