<x-app-layout>
    <x-slot name="title">Daftar Kebutuhan Barang Proyek</x-slot>

    @if (session()->has('success'))
        <x-alert type="success" message="{{ session()->get('success') }}" />
    @endif

    <x-card>
        <x-slot name="title">Semua Kebutuhan Barang Proyek</x-slot>
        <x-slot name="option">
            <div>
                <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Filter Proyek
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a href="{{ route('admin.proyek.index') }}" class="dropdown-item">Semua Proyek</a>
                    @foreach ($proyeks as $row)
                        <a class="dropdown-item"
                            href="{{ route('admin.proyek.index') }}?filter_proyek={{ $row->nama_proyek }}">{{ $row->nama_proyek }}</a>
                    @endforeach
                </div>
                @if (Auth::user()->roles[0]->name == 'Estimator')
                    {{-- <button class="btn btn-primary add"><i class="fas fa-plus"></i> Tambah</button> --}}
                    <a href="{{route('admin.proyek.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
                @endif
            </div>
        </x-slot>

        <div class="table-responsive">
            <table class="display table table-striped table-hover" id="daftar">
                <thead>
                    <tr>
                        @if (Auth::user()->roles[0]->name == 'Admin')
                            <th>Estimator</th>
                        @endif
                        <th>Nama Proyek</th>
                        <th>Tanggal</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Barang</th>
                        @if (Auth::user()->roles[0]->name == 'Estimator')
                            <th style="width: 10%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($payload as $row)
                        <tr>
                            @if (Auth::user()->roles[0]->name == 'Admin')
                                <td>{{$payload[$i]['user'] }}</td>
                            @endif
                            <td >{{ $payload[$i]['nama_proyek'] }}</td>
                            <td>
                                {{$payload[$i]['created_at'] }}
                            </td>
                            <td>
                                <ul>
                                    @foreach ($payload[$i]['barangs'] as $item )
                                        <li>{{ $item->nama }} </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul>
                                    @foreach ($payload[$i]['jumlah'] as $item )
                                        <li>{{ $item }} </li>
                                    @endforeach
                                </ul>

                            </td>
                            @if (Auth::user()->roles[0]->name == 'Estimator')
                                <td class="text-center" >
                                    {{-- <button class="btn btn-sm btn-primary edit" data-id="{{ $payload[$i]['id'] }}"><i
                                            class="fas fa-edit"></i></button> --}}
                                            <a href="{{url('/admin/proyek/info/'. $payload[$i]['id'])}}"  class="btn btn-sm  btn-primary"><i class="fas fa-edit"></i> </a>
                                    <form action="{{ route('admin.proyek.destroy', $payload[$i]['id']) }}"
                                        style="display: inline-block;" method="POST">
                                        @csrf
                                        <button type="button" class="btn btn-sm btn-danger delete"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            @endif
                        </tr>


                        @php
                            $i++;
                        @endphp
                    @endforeach

                </tbody>
            </table>
        </div>
    </x-card>


    <style>
        .overlay th {
    background-color: #212121;
    color: white;
    position: absolute;
    top:0;
}
    </style>

    {{-- add model --}}
    <x-modal>
        <x-slot name="title">
            <h6 class="m-0 font-weight-bold text-primary">Tambahkan Kebutuhan Barang</h6>
        </x-slot>
        <x-slot name="id">add</x-slot>


        <form action="{{ route('admin.proyek.store') }}" method="post" class="form-group">
            @csrf
            {{-- <x-slot name="sticky"> --}}
                <div class="form-group"  >
                    <label for="">Nama Proyek</label>
                    <input type="text" class="form-control" name="nama_proyek" required="">
                    {{-- <select name="nama_proyek" id=""></select> --}}
                </div>
            {{-- </x-slot> --}}
            {{-- <div class="form-barang"> --}}
                <div class="form-group d-flex" style="position: relative;" >
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Jumlah Barang</th>
                            </tr>
                        </thead>
                        <tbody class="form-barang">
                            <tr>
                                <td>
                                    <select name="barang_id[]"  data-live-search="true" class="form-control " id="barang_id" required>
                                        <option value="">--- Pilih Barang ---</option>
                                        @foreach ($barangs as $row)
                                        <option value="{{ $row->id }}">{{ $row->nama }}</option>
                                        @endforeach
                                        </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="jumlah[]" required>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                    {{-- <div class="col-md-6 pl-0">
                        <label for="">Barang</label>

                    </div>

                    <div class="col-md-6 pr-0">
                    </div>
                    </div> --}}
            {{-- </div> --}}
            {{-- <div class="form-group">
                <label for="">Jumlah Barang</label>
                <input type="number" class="form-control" name="jumlah" required="">
            </div> --}}
            {{-- <div style="margin-top: 250px"> --}}
                {{-- <x-slot name="footer"> --}}
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-info btn-tambah-barang">Tambah barang</button>
                {{-- </x-slot> --}}
            {{-- </div> --}}
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
            <input type="hidden" id="id_proyek" name="id" value="">
            <div class="form-group">
                <label for="">Nama Proyek</label>
                <input type="text" class="form-control" name="nama_proyek" required="">
            </div>
            <div class="form-barang">

            </div>
            <button type="button" class="btn btn-info btn-tambah-barang">Tambah barang</button>
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



            $('body').on('click', '.btn-tambah-barang', function() {
                    $('.form-barang').append(`
                            <tr>
                                <td>
                                    <select name="barang_id[]"  data-live-search="true" class="form-control " id="barang_id" required>
                                        <option value="">--- Pilih Barang ---</option>
                                        @foreach ($barangs as $row)
                                        <option value="{{ $row->id }}">{{ $row->nama }}</option>
                                        @endforeach
                                        </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="jumlah[]" required>
                                </td>
                            </tr>`)

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
                $('#daftar').DataTable({
                    dom: 'Bfrtip',
			        buttons: [
			            'excelHtml5',
			            'csvHtml5',
			            'pdfHtml5'
			        ]
                });
            });
        </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('select').selectpicker();
        });
    </script>
    </x-slot>
</x-app-layout>
