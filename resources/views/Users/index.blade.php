@extends('layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Users</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Admin</a></li>
        <li class="breadcrumb-item active"><a href="/users">Users</a></li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  @if (session()->has('success'))
    <x-alert-success :message="session('success')" />
  @endif

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title">Daftar User</h5>
              </div>
            </div>

            <!-- Table with stripped rows -->
            <div class="table table-responsive">
              <table class="table" id="user-table">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Email</th>
                    <th>No. Telp</th>
                    <th>Admin/User</th>
                    <th>Username Telegram</th>
                    <th>Tanggal Buat</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($users->isNotEmpty())
                    @foreach ($users as $user)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }} @if (auth()->user()->id === $user->id)
                            (Anda)
                          @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone_number }}</td>
                        <td>
                          {{ $user->is_admin ? 'Admin' : 'User' }}
                        </td>
                        <td>{{ $user->username ? $user->username : 'Belum ada' }}</td>
                        <td class="text-center align-middle" style="padding: 0;">
                          <span
                            style="color:#219653; background-color: #e8f4ed; border-radius: 10px; padding: 3px 5px; display: inline-block; box-sizing: border-box">
                            {{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y H:i:s') }}
                          </span>
                        </td>
                        <td>
                          <a href="/users/{{ $user->id }}/edit" type="button" class="btn btn-warning"><i
                              class="bi bi-pencil"></i></a>
                          <button type="button" class="btn btn-danger" id="btn-reject-approval"
                            onclick="rejectRequestConfirmation('{{ $user->id }}')"><i
                              class="bi bi-trash"></i></button>
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
      var table = $('#user-table').DataTable({
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
    });

    function refreshUserTable() {
      $.ajax({
        url: '/users',
        type: 'GET',
        dataType: 'html',
        success: function(data) {
          // Ambil tbody baru dari response dan replace tbody lama
          var newTbody = $(data).find('#user-table tbody').html();
          $('#user-table tbody').html(newTbody);
        }
      });
    }
  </script>
@endsection
