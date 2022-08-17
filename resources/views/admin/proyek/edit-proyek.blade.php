<x-app-layout>
    <x-slot name="title">Tambah Kebutuhan Barang Proyek</x-slot>
    <form action="{{ route('admin.proyek.update') }}" method="post" id="edit" class="form-group">
        @csrf
        <input type="hidden" id="id_proyek" name="id" value="">
        <div class="form-group">
            <label for="">Nama Proyek</label>
            <input type="text" class="form-control" value="{{$payload['nama_proyek']}}" name="nama_proyek" required="">
        </div>
        {{-- <div class="form-barang">
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

        </div> --}}
        <div class="form-group d-flex" style="position: relative;" >
            <input type="text" name="id" value="{{$payload['id']}}" hidden>
            <table class="table">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Jumlah Barang</th>
                    </tr>
                </thead>
                <tbody class="form-barang">
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($payload['barang'] as $item)

                    <tr>
                        <td>
                            <select name="barang_id[]"  data-live-search="true" class="form-control " id="barang_id" required>
                                <option value="">--- Pilih Barang ---</option>
                                @foreach ($barangs as $row)
                                <option value="{{ $row->id }}" {{$item->id == $row->id ? 'selected' : ''}}>{{ $row->nama }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" value="{{$payload['jumlah'][$i]}}" class="form-control" name="jumlah[]" required>
                        </td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
        <button type="button" class="btn btn-info btn-tambah-barang">Tambah barang</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>

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
