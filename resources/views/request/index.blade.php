@extends('layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Approvals</h1>
    <nav>
      <ol class="breadcrumb">
        {{-- <li class="breadcrumb-item"><a href="/dashboard/admin">Admin</a></li> --}}
        <li class="breadcrumb-item active"><a href="/">Request Approvals</a></li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title">Daftar Permintaan Persetujuan</h5>
              </div>
              <div class="col-12 col-md-4 mt-3">
                <div class="btn-group w-100 w-md-auto" role="group">
                  <a href="{{ route('request-approval.index') }}"
                    class="btn btn-outline-primary {{ Request::is('request-approval') ? 'active' : '' }}">Pending</a>
                  <a href="{{ route('request-approval.approved') }}"
                    class="btn btn-outline-primary {{ Request::is('request-approval/approved') ? 'active' : '' }}">Diterima</a>
                  <a href="{{ route('request-approval.rejected') }}"
                    class="btn btn-outline-primary {{ Request::is('request-approval/rejected') ? 'active' : '' }}">Ditolak</a>
                  <a href="{{ route('request-approval.all') }}"
                    class="btn btn-outline-primary {{ Request::is('request-approval/all') ? 'active' : '' }}">Semua</a>
                </div>
              </div>
            </div>

            <!-- Table with stripped rows -->
            <div class="table table-responsive">
              <table class="table" id="request-approval-table">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>User Request</th>
                    <th>Nama Stock</th>
                    <th>Jumlah Stock</th>
                    <th>Masuk/Keluar</th>
                    <th>Tanggal Request</th>
                    <th>Status Approval</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($requestApprovals->isNotEmpty())
                    @foreach ($requestApprovals as $request)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $request->user->name }} @if (auth()->user()->id === $request->user->id)
                            (Anda)
                          @endif
                        </td>
                        <td>{{ $request->stock->name }}</td>
                        <td>{{ $request->amount }}</td>
                        <td>
                          @if ($request->is_entry)
                            Barang masuk
                          @else
                            Barang keluar
                          @endif
                        </td>
                        <td class="text-center align-middle" style="padding: 0;">
                          <span
                            style="color:#219653; background-color: #e8f4ed; border-radius: 10px; padding: 3px 5px; display: inline-block; box-sizing: border-box">
                            {{ \Carbon\Carbon::parse($request->created_at)->format('d-m-Y H:i:s') }}
                          </span>
                        </td>
                        @if ($request->status == 'pending')
                          <td class="text-center align-middle">
                            <span
                              style="color:#f2c94c; background-color: #fdf3e0; border-radius: 10px; padding: 3px 5px; display: inline-block; box-sizing: border-box">
                              Pending
                            </span>
                          </td>
                        @elseif($request->status == 'approved')
                          <td class="text-center align-middle">
                            <span
                              style="color:#27ae60; background-color: #e8f4ed; border-radius: 10px; padding: 3px 5px; display: inline-block; box-sizing: border-box">
                              Approved
                            </span>
                          </td>
                        @elseif($request->status == 'rejected')
                          <td class="text-center align-middle">
                            <span
                              style="color:#eb5757; background-color: #fbe9e9; border-radius: 10px; padding: 3px 5px; display: inline-block; box-sizing: border-box">
                              Rejected
                            </span>
                          </td>
                        @endif
                        <td>
                          @if (auth()->user()->is_admin)
                            @if ($request->status == 'pending')
                              <button type="button" class="btn btn-success" id="btn-accept-approval"
                                onclick="acceptRequestConfirmation('{{ $request->id }}')">Terima</button>
                              <button type="button" class="btn btn-danger" id="btn-reject-approval"
                                onclick="rejectRequestConfirmation('{{ $request->id }}')">Tolak</button>
                            @else
                              <p class="card-text">Sudah diapprove</p>
                            @endif
                          @else
                            <p>Anda tidak memiliki akses</p>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>
            <!-- End Table with stripped rows -->
          </div>
        </div>
      </div>
    </div>
  </section>

  <script>
    $(document).ready(function() {
      var table = $('#request-approval-table').DataTable({
        scrollX: true,
        columns: [{
            data: 'no',
            defaultContent: '<i>Not set</i>'
          },
          {
            data: 'user',
            defaultContent: '<i>Not set</i>'
          },
          {
            data: 'name',
            defaultContent: '<i>Not set</i>'
          },
          {
            data: 'amount',
            defaultContent: '<i>Not set</i>'
          },
          {
            data: 'is_entry',
            defaultContent: '<i>Not set</i>'
          },
          {
            data: 'created_at',
            defaultContent: '<i>Not set</i>'
          },
          {
            data: 'status',
            defaultContent: '<i>Not set</i>'
          },
          {
            data: 'action',
            defaultContent: '<i>Not set</i>'
          },
        ],
        dom: 'Bfrtip', // Menambahkan tombol ekspor ke DOM
        buttons: [
          'csv', 'excel', 'pdf', 'print'
        ]

      });

      // Reset button functionality (updated)
      // $('#tombolReset').on('click', function() {
      //   table.search('').columns().search('').draw();
      // });
    });

    function refreshStockTable() {
      $.ajax({
        url: '/request-approval',
        type: 'GET',
        dataType: 'html',
        success: function(data) {
          // Ambil tbody baru dari response dan replace tbody lama
          var newTbody = $(data).find('#request-approval-table tbody').html();
          $('#request-approval-table tbody').html(newTbody);
        }
      });
    }

    function acceptRequestConfirmation(requestId) {
      Swal.fire({
        title: "Yakin ingin menerima request approval ini?",
        text: "Aksi ini tidak bisa mengembalikan data ke semula!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2980B9",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, accept it!"
      }).then((result) => {
        if (result.isConfirmed) {
          console.log("Accept confirmed. Trying to add stock data...");
          // document.getElementById('deleteForm' + stockId).submit();
          // $(`#deleteForm${stockId}`).submit();
          submitAcceptRequest(requestId);
        }
      });
    }

    function submitAcceptRequest(requestId) {
      // var form = $(`#deleteForm${stockId}`);
      var url = `/request-approval/accept/${requestId}`;
      // var formData = form.serialize();

      $.ajax({
        url: url,
        type: 'POST',
        // data: formData,
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        success: function(response) {
          console.log("Successfully accepted request approval and add stock:", response);

          $('#dynamic-alert').remove();

          var alertHtml = `
          <div class="row justify-content-center" id="dynamic-alert">
            <div class="alert alert-success alert-dismissible fade show col-lg-12 justify-content-center" role="alert">
              ${response.message}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          </div>
        `;

          // $('#dynamic-alert').remove();
          $('.pagetitle').after(alertHtml);

          // Refresh tabel stock (ambil ulang data via AJAX)
          refreshStockTable();
        },
        error: function(xhr) {
          // Hapus alert error sebelumnya jika ada
          $('#dynamic-alert').remove();

          // Ambil pesan error dari response
          var message = 'Terjadi kesalahan.';
          if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
          } else if (xhr.responseJSON && xhr.responseJSON.errors) {
            message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
          }

          var alertHtml = `
            <div class="row justify-content-center" id="dynamic-alert">
              <div class="alert alert-danger alert-dismissible fade show col-lg-12 justify-content-center" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            </div>
          `;
          $('.pagetitle').after(alertHtml);

          var errors = xhr.responseJSON && xhr.responseJSON.errors ? xhr.responseJSON.errors : null;
          if (errors) {
            if (errors.name) {
              $('#name').addClass('is-invalid');
              $('#name').next('.invalid-feedback').text(errors.name[0]);
            }
            if (errors.amount) {
              $('#amount').addClass('is-invalid');
              $('#amount').next('.invalid-feedback').text(errors.amount[0]);
            }
          }
        }
      });
    };

    function rejectRequestConfirmation(requestId) {
      Swal.fire({
        title: "Yakin ingin menolak request approval stock ini?",
        text: "Aksi ini tidak bisa mengembalikan data ke semula!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2980B9",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, reject it!"
      }).then((result) => {
        if (result.isConfirmed) {
          console.log("reject confirmed. Trying to reject approval...");
          // document.getElementById('deleteForm' + stockId).submit();
          // $(`#deleteForm${stockId}`).submit();
          submitRejectRequest(requestId);
        }
      });
    }

    function submitRejectRequest(requestId) {
      var url = `/request-approval/reject/${requestId}`;

      $.ajax({
        url: url,
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        success: function(response) {
          console.log("Successfully reject request approval:", response);

          $('#dynamic-alert').remove();

          var alertHtml = `
          <div class="row justify-content-center" id="dynamic-alert">
            <div class="alert alert-success alert-dismissible fade show col-lg-12 justify-content-center" role="alert">
              ${response.message}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          </div>
        `;

          // $('#dynamic-alert').remove();
          $('.pagetitle').after(alertHtml);

          // Refresh tabel stock (ambil ulang data via AJAX)
          refreshStockTable();
        },
        error: function(xhr) {
          // Hapus alert error sebelumnya jika ada
          $('#dynamic-alert').remove();

          // Ambil pesan error dari response
          var message = 'Terjadi kesalahan.';
          if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
          } else if (xhr.responseJSON && xhr.responseJSON.errors) {
            message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
          }

          var alertHtml = `
            <div class="row justify-content-center" id="dynamic-alert">
              <div class="alert alert-danger alert-dismissible fade show col-lg-12 justify-content-center" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            </div>
          `;
          $('.pagetitle').after(alertHtml);

          var errors = xhr.responseJSON && xhr.responseJSON.errors ? xhr.responseJSON.errors : null;
          if (errors) {
            if (errors.name) {
              $('#name').addClass('is-invalid');
              $('#name').next('.invalid-feedback').text(errors.name[0]);
            }
            if (errors.amount) {
              $('#amount').addClass('is-invalid');
              $('#amount').next('.invalid-feedback').text(errors.amount[0]);
            }
          }
        }
      });
    };
  </script>
@endsection
