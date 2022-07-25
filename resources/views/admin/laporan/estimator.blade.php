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
                    <button class="btn btn-primary add"><i class="fas fa-plus"></i> Tambah</button>
                @endif
            </div>
        </x-slot>

        <div class="table-responsive">
            <table class="display table table-striped table-hover" id="daftar">
                <thead>
                    <tr>

                        <th>Nama Proyek</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Barang</th>

                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($payload as $row)
                        <tr>

                            <td >{{ $payload[$i]['nama_proyek'] }}</td>
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

                        </tr>


                        @php
                            $i++;
                        @endphp
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
            <div class="form-barang">
                <div class="form-group d-flex">
                    <div class="col-md-6 pl-0">
                        <label for="">Barang</label>

                        <select name="barang_id[]"  data-live-search="true" class="form-control " id="barang_id" required>
                            <option value="">--- Pilih Barang ---</option>
                            @foreach ($barangs as $row)
                            <option value="{{ $row->id }}">{{ $row->nama }}</option>
                            @endforeach
                            </select>
                    </div>

                    <div class="col-md-6 pr-0">
                        <label for="">Jumlah Barang</label>
                        <input type="number" class="form-control" name="jumlah[]" required>
                    </div>
                    </div>
            </div>
            {{-- <div class="form-group">
                <label for="">Jumlah Barang</label>
                <input type="number" class="form-control" name="jumlah" required="">
            </div> --}}
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-info btn-tambah-barang">Tambah barang</button>
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
                var id = $(this).data('id')
                $('body #id_proyek').val(id)

                $.ajax({
                    url: `{{ route('admin.proyek.info') }}?id=${id}`,
                    type: 'GET',
                    success: function(data) {
                        console.log(data);
                        // console.log(data);
                        $('.form-barang').html('')
                        $('#edit input[name="nama_proyek"]').val(data.nama_proyek)
                        var barId = 0;
                        $.each(data.barang, function(index, value) {
                            // console.log(value.id);
                            barId = value.id;
                            var string = '$id == "'+ value.id +'" ? "true"   : $id';
                            console.log(string);
                            var query = "$row->id == '"+ barId +"' ? selected : ''";
                            console.log(query);
                            var newQ = `{{`+ query + `}}`
                            $('.form-barang').append(`
                            <div class="form-group d-flex">
                                <div class="col-md-6 pl-0">
                                    <label for="">Barang</label>
                                    <select name="barang_id[]"  data-live-search="true" class="form-control edit_select_`+value.id+`" id="barang_id">
                                        <option value="">--- Pilih Barang ---</option>

                                    </select>
                                </div>
                                <div class="col-md-6 pr-0">
                                    <label for="">Jumlah Barang</label>
                                    <input type="number" class="form-control" value='${data.jumlah[index]}' name="jumlah[]">
                                </div>
                            </div>
                            `)

                            // $('.form-barang #barang_id option[value='+ barId+']').attr('selected', true);
                            $.ajax({
                                url: '/admin/barang-all',
                                type: 'GET',
                                success: function(data) {
                                    $.each(data, function(k, v) {
                                        console.log(value.id + ' ' +v.id);
                                        // if ($('.edit_select_'+barId+'')) {
                                            $('.edit_select_'+value.id+'')
                                            .append($("<option></option>")
                                                .attr("value", v.id)
                                                .prop('selected', v.id == value.id ? true :false)
                                                .text(v.nama));
                                        // }
                                    })
                                }
                            })
                        })
                    }
                })



                $('#edit').modal('show')
            })

            $('body').on('click', '.btn-tambah-barang', function() {
                    $('.form-barang').append(`
                    <div class="form-group d-flex">
                        <div class="col-md-6 pl-0">
                            <label for="">Barang</label>

                            <select name="barang_id[]"  data-live-search="true" class="form-control " id="barang_id">
                                <option value="">--- Pilih Barang ---</option>
                                @foreach ($barangs as $row)
                                <option value="{{ $row->id }}">{{ $row->nama }}</option>
                                @endforeach
                                </select>
                        </div>

                        <div class="col-md-6 pr-0">
                            <label for="">Jumlah Barang</label>
                            <input type="number" class="form-control" name="jumlah[]">
                        </div>
                        </div>`)

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
