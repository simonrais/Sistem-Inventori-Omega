<x-app-layout>
    <x-slot name="title">Tambah Kebutuhan Barang Proyek</x-slot>
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
                    url: `{{ url('admin/proyek/info/${id}') }}`,
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

                            <tr>
                                <td>
                                    <select name="barang_id[]"  data-live-search="true" class="form-control   edit_select_`+value.id+`" id="barang_id" required>
                                        <option value="">--- Pilih Barang ---</option>
                                        @foreach ($barangs as $row)
                                        <option value="{{ $row->id }}">{{ $row->nama }}</option>
                                        @endforeach
                                        </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control"  value='${data.jumlah[index]}' name="jumlah[]" required>
                                </td>
                            </tr>
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


