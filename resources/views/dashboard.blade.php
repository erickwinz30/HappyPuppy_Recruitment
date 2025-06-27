@extends('layout.main')

@section('container')
  <div class="pagetitle">
    <div class="d-flex justify-content-between">
      <div>
        <h1>Dashboard</h1>
        <nav>
          <ol class="breadcrumb">
            {{-- <li class="breadcrumb-item"><a href="/dashboard/admin">Admin</a></li> --}}
            <li class="breadcrumb-item active"><a href="/">Dashboard</a></li>
          </ol>
        </nav>
      </div>
      @php
        $month = '';
        $year = '';
        if (request()->has('month')) {
            $month = request()->get('month');
        }
        if (request()->has('year')) {
            $year = request()->get('year');
        }
      @endphp
      <form onsubmit="dashboardFilter(event)" class="text-end">
        <div class="d-flex gap-2 mb-2">
          <select id="filter-month" class="form-select">
            <option {{ $month == '' ? 'selected' : '' }} value="">Bulan..</option>
            <option {{ $month == '1' ? 'selected' : '' }} value="1">Januari</option>
            <option {{ $month == '2' ? 'selected' : '' }} value="2">Februari</option>
            <option {{ $month == '3' ? 'selected' : '' }} value="3">Maret</option>
            <option {{ $month == '4' ? 'selected' : '' }} value="4">April</option>
            <option {{ $month == '5' ? 'selected' : '' }} value="5">Mei</option>
            <option {{ $month == '6' ? 'selected' : '' }} value="6">Juni</option>
            <option {{ $month == '7' ? 'selected' : '' }} value="7">Juli</option>
            <option {{ $month == '8' ? 'selected' : '' }} value="8">Agustus</option>
            <option {{ $month == '9' ? 'selected' : '' }} value="9">September</option>
            <option {{ $month == '10' ? 'selected' : '' }} value="10">Oktober</option>
            <option {{ $month == '11' ? 'selected' : '' }} value="11">November</option>
            <option {{ $month == '12' ? 'selected' : '' }} value="12">Desember</option>
          </select>
          <input id="filter-year" type="text" class="form-control" value="{{ $year }}" type="number">
        </div>
        <button class="btn btn-primary ms-auto" type="submit">Terapkan</button>
        <a href="/" class="btn btn-primary ms-auto" type="button">Reset</a>
      </form>
    </div>
  </div><!-- End Page Title -->

  {{-- @if (session()->has('success'))
    <x-alert-success :message="session('success')" />
  @endif
  <div class="row justify-content-center">
    <div class="alert alert-success alert-dismissible fade show col-lg-12" role="alert">
      {{ $message }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div> --}}

  {{-- @if (session()->has('error'))
    <x-alert-error :message="session('error')" />
  <div class="row justify-content-center">
    <div class="alert alert-danger alert-dismissible fade show col-lg-12 justify-content-center" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
  @endif --}}


  <section class="section dashboard">
    <div class="row">

      <!-- Left side columns -->
      <div class="col-lg-8">
        <div class="row">

          <!-- Sales Card -->
          <div class="col-xxl-4 col-md-6">
            <div class="card info-card sales-card">

              <div class="card-body">
                <h5 class="card-title">Total Stok</span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-cart"></i>
                  </div>
                  <div class="ps-3">
                    <h6>{{ $widget['total_stock'] }}</h6>
                  </div>
                </div>
              </div>

            </div>
          </div><!-- End Sales Card -->

          <!-- Revenue Card -->
          <div class="col-xxl-4 col-md-6">
            <div class="card info-card revenue-card">

              <div class="card-body">
                <h5 class="card-title">Stok Masuk</span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-currency-dollar"></i>
                  </div>
                  <div class="ps-3">
                    <h6>{{ $widget['total_stock_in'] }}</h6>

                  </div>
                </div>
              </div>

            </div>
          </div><!-- End Revenue Card -->

          <!-- Customers Card -->
          <div class="col-xxl-4 col-xl-12">

            <div class="card info-card customers-card">

              <div class="card-body">
                <h5 class="card-title">Stok Keluar</span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-people"></i>
                  </div>
                  <div class="ps-3">
                    <h6>{{ $widget['total_stock_out'] }}</h6>

                  </div>
                </div>

              </div>
            </div>

          </div><!-- End Customers Card -->

        </div>
        <!-- Website Traffic -->
        <div class="card">

          <div class="card-body pb-0">
            <h5 class="card-title">Website Traffic <span>| Today</span></h5>

            <div id="trafficChart"
              style="min-height: 400px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); position: relative;"
              class="echart" _echarts_instance_="ec_1750661261552">
              <div style="position: relative; width: 562px; height: 400px; padding: 0px; margin: 0px; border-width: 0px;">
                <canvas data-zr-dom-id="zr_0" width="983" height="700"
                  style="position: absolute; left: 0px; top: 0px; width: 562px; height: 400px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px; margin: 0px; border-width: 0px;"></canvas>
              </div>
              <div class=""></div>
            </div>

          </div>
        </div><!-- End Website Traffic -->
      </div>

      <!-- Right side columns -->
      <div class="col-lg-4">

        <!-- Recent Activity -->
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Aktifitas Terakhir</span></h5>

            <div class="activity">

              @foreach ($activities as $item)
                @php
                  $status =
                      $item->status == 'pending' ? 'warning' : ($item->status == 'approved' ? 'success' : 'danger');
                @endphp
                <div class="activity-item d-flex">
                  <div class="activite-label">2 hrs</div>
                  <i class="bi bi-circle-fill activity-badge align-self-start text-{{ $status }}"></i>
                  <div class="activity-content">
                    @if ($item->is_entry)
                      <span class="badge bg-{{ $status }}">({{ ucfirst($item->status) }})</span><br>
                      <b>Masuk</b> : {{ $item->stock->name }} sebanyak {{ $item->amount }}
                    @else
                      <span class="badge bg-{{ $status }}">({{ ucfirst($item->status) }})</span><br>
                      ({{ ucfirst($item->status) }})
                      <b>Keluar</b> : {{ $item->stock->name }} sebanyak {{ $item->amount }}
                    @endif
                  </div>
                </div><!-- End activity item-->
              @endforeach

            </div>

          </div>
        </div><!-- End Recent Activity -->

      </div><!-- End Right side columns -->

    </div>
  </section>
  <script>
    function dashboardFilter(event) {
      event.preventDefault()
      let month = document.querySelector('#filter-month').value
      let year = document.querySelector('#filter-year').value

      if (!year || !month) {
        alert('harap isi tahun atau bulan sebelum melanjutkan')
        return
      }
      if (!/^\d{4}$/.test(year)) {
        alert('Tahun harus terdiri dari 4 digit angka')
        return
      }

      window.location.replace(`/?month=${month}&year=${year}`)
    }
    document.addEventListener("DOMContentLoaded", () => {
      echarts.init(document.querySelector("#trafficChart")).setOption({
        tooltip: {
          trigger: 'item'
        },
        legend: {
          top: '5%',
          left: 'center'
        },
        series: [{
          name: 'Access From',
          type: 'pie',
          radius: ['40%', '70%'],
          avoidLabelOverlap: false,
          label: {
            show: false,
            position: 'center'
          },
          emphasis: {
            label: {
              show: true,
              fontSize: '18',
              fontWeight: 'bold'
            }
          },
          labelLine: {
            show: false
          },
          data: [
            @foreach ($stocks as $item)
              {
                'value': {{ $item->amount_sum }},
                'name': '{{ $item->stock->name }}',
              },
            @endforeach
          ]
        }]
      });
    });
  </script>
@endsection
