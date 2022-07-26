<x-app-layout>
	<x-slot name="head">
		<link rel="stylesheet" href="{{ asset('dist/vendor/datatables/buttons.dataTables.min.css') }}">
	</x-slot>
	<x-slot name="title">Laporan</x-slot>

	@if(session()->has('success'))
	<x-alert type="success" message="{{ session()->get('success') }}" />
	@endif

	<x-card>
        {{-- <style></style> --}}
		<x-slot name="title">Semua Laporan</x-slot>
        <div class="d-flex mb-2 justify-content-between p-0">
            <form class="col-md-8 d-flex" action="{{route('admin.laporan.index')}}" method="GET">
                @if (Auth::user()->roles[0]->name == 'Admin')
                <label for="" class="col-md-1">Dari</label>
                <input type="text" class="form-control form-control-sm col-md-4 daterange" name="tanggal">
                @endif

                <label for="" class="col-md-1 text-start p-0">Kategori</label>
                <select class="form-control form-control-sm col-md-4" name="kategori">
                    <option value="">-- pilih Jenis --</option>
                    <option value="masuk">Barang Masuk</option>
                    <option value="keluar">Barang Keluar</option>
                </select>
                <button class="btn btn-sm btn-success col-md-2 mx-3" type="submit">Cari</button>
            </form>
            <button class="btn btn-sm btn-info col-md-1" id="semua" type="button">Semua</button>
        </div>
		<div class="table-responsive">
		<table class="table table-hover mb-3">
			<thead>
				<th>Nama Barang</th>
				<th>Proyek</th>
				<th>Harga</th>
				<th>Stok</th>
				<th>Tanggal</th>
				<th>Aksi</th>
			</thead>
			<tbody>
				@foreach ($data as $row)
					<tr>
						<td>{{ $row->nama }}</td>
						<td>{{ $row->orang }}</td>
						<td>{{ $row->harga }}</td>
						<td>{{ $row->jumlah }}</td>
						<td>{{ date('Y-m-d',strtotime($row->created_at)) }}</td>
						<td><span class="badge badge-{{ ($row->jenis == 'masuk') ? 'success' : 'danger' }}">{{ $row->jenis }}</span></td>
					</tr>
				@endforeach

			</tbody>
		</table>
		</div>
	</x-card>


	<x-slot name="script">
		<script src="{{ asset('dist/vendor/datatables/jquery.dataTables.min.js') }}"></script>
		<script src="{{ asset('dist/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
		<script src="{{ asset('dist/vendor/datatables/dataTables.buttons.min.js') }}"></script>
		<script src="{{ asset('dist/vendor/datatables/jszip.min.js') }}"></script>
		<script src="{{ asset('dist/vendor/datatables/pdfmake.min.js') }}"></script>
		<script src="{{ asset('dist/vendor/datatables/vfs_fonts.js') }}"></script>
		<script src="{{ asset('dist/vendor/datatables/buttons.html5.min.js') }}"></script>
		<script>

			$(document).ready(function () {
		      $('table').DataTable({
			        dom: 'Bfrtip',
			        buttons: [
			            'excelHtml5',
			            'csvHtml5',
			            'pdfHtml5'
			        ]
			    } );

                // $('.dataTables_filter').addClass('daterange')
		    });

            $('.daterange').daterangepicker();
            $('#semua').on('click', function () {
                var loc = location.href.split('?')[0];
                location.href = loc;
            })
		</script>
	</x-slot>
</x-app-layout>
