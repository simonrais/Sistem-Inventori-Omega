<x-app-layout>
  
    <x-slot name="title">
        Dahsboard
    </x-slot>
  
    <section class="row">
      @if (Auth::user()->roles[0]->name == 'Admin' )
      <x-card-sum 
      
            text="Total User" 
            value="{{ $user }}" 
            icon="user" 
            color="warning"
        />
        @endif
        
         @if (Auth::user()->roles[0]->name == 'Admin' || Auth::user()->roles[0]->name == 'Staff Gudang')
        <x-card-sum 
            text="Total Supplier" 
            value="{{ $supplier }}" 
            icon="users" 
            color="warning"
        />
        
        @endif
        
        @if (Auth::user()->roles[0]->name == 'Admin'  )
        <x-card-sum 
            text="Total Barang" 
            value="{{ $jumlah_barang }}" 
            icon="box" 
            color="primary"
        />
        @endif
  
        @if (Auth::user()->roles[0]->name == 'Admin' || Auth::user()->roles[0]->name == 'Staff Gudang') 
        <x-card-sum 
            text="Transaksi Barang Masuk" 
            value="{{ $barang_masuks }}" 
            icon="download" 
            color="success"
        />
  
        <x-card-sum 
            text="Transaksi Barang Keluar" 
            value="{{ $barang_keluars }}" 
            icon="upload" 
            color="danger"
        />
        @endif
      
        <x-card-sum 
            text="Jumlah Proyek" 
            value="{{ $proyek }}" 
            icon="building" 
            color="danger"
        />
        
    </section>
    @if (Auth::user()->roles[0]->name == 'Admin' )
    <section class="row">
        {{-- log activity section --}}
        
        <div class="col-md-12">
          <x-card>
            <div class="card-header">
              <div class="card-title"><h6 class="m-0 font-weight-bold text-primary" float="center">Jumlah Barang Masuk & Barang Keluar</h6></div>
            </div>
            
            <div class="card-body">
              <div class="chart-container" style="width: 100%; height: 80vh">
                <canvas id="barChart"></canvas>
              </div>
            </div>
            
          </x-card>
        </div>
    </section>
    <x-slot name="script">
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      
      {{-- <div style="width:75%;">
          <canvas id="myChart"></canvas>
      </div> --}}
     
      <script>
        const ctx = document.getElementById('barChart');
        const barChart = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
              'Oktober', 'November', 'Desember'],
                datasets: [{
                    label: 'Barang Masuk',
                    data: [{{$masuk_jan}}, {{$masuk_feb}},{{$masuk_mar}}, {{$masuk_apr}}, {{$masuk_mei}},{{$masuk_jun}}, 
                    {{$masuk_jul}}, {{$masuk_agu}},{{$masuk_sep}}, {{$masuk_okt}}, {{$masuk_nov}}, {{$masuk_des}} ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        
  
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                       
                    ],
                    borderWidth: 1
                },
                {
                    label: 'Barang Keluar',
                    data: [{{$keluar_jan}}, {{$keluar_feb}}, {{$keluar_mar}}, {{$keluar_apr}}, {{$keluar_mei}}, {{$keluar_jun}},
                    {{$keluar_jul}}, {{$keluar_agu}}, {{$keluar_sep}}, {{$keluar_okt}}, {{$keluar_nov}}, {{$keluar_des}} ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)'
  
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)'
    
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                      ticks:{
                        beginAtZero:true
                    }
                }]
              }
            }
        });
        </script>
    </x-slot>
    @endif
  
  </x-app-layout>