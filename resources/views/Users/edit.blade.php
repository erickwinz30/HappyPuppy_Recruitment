@extends('layout.main')

@section('container')
  <div class="pagetitle">
    <h1>User</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">User</li>
        <li class="breadcrumb-item active">Edit</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  @if (session()->has('error'))
    <x-alert-error :message="session('error')" />
  @endif

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Edit User</h5>
            <form action="/users/{{ $user->id }}" method="POST" id="editForm">
              @method('put')
              @csrf
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
                <div class="mb-3">
                  <label for="name" class="form-label @error('name') is-invalid @enderror">Nama User</label>
                  <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $user->name) }}" required autofocus>
                  @error('name')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label @error('email') is-invalid @enderror">Email</label>
                  <input type="text" class="form-control" id="email" name="email"
                    value="{{ old('email', $user->email) }}" required autofocus>
                  @error('email')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="alert alert-warning" role="alert">
                Untuk menambah akses admin, memerlukan username telegram. Silahkan melakukan interaksi/chat dengan bot.
                Jika sudah maka masukkan username telegram anda.
              </div>
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
                <div>
                  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
                    <div class="mb-3">
                      <label for="phone_number" class="form-label @error('phone_number') is-invalid @enderror">No.
                        Telepon</label>
                      <input type="text" inputmode="numeric" class="form-control" id="phone_number" name="phone_number"
                        value="{{ old('phone_number', $user->phone_number) }}" required autofocus>
                      @error('phone_number')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                    <div class="mb-3">
                      <label for="username" class="form-label @error('username') is-invalid @enderror">Username</label>
                      <input type="text" inputmode="numeric" class="form-control" id="username" name="username"
                        value="{{ old('username', $user->username) }}" required autofocus>
                      @error('username')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                  </div>
                </div>
                <div class="mb-3">
                  <h6 class="card-text fw-semibold mt-3">Akses Admin / User</h6>
                  <div class="d-flex justify-content-evenly align-items-center">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="is_admin" id="is_admin" value="1"
                        {{ $user->is_admin == 1 ? 'checked' : '' }} />
                      <label class="form-check-label" for="is_admin">
                        Admin </label>
                    </div>
                    <div class="form-check ms-3">
                      <input class="form-check-input" type="radio" name="is_admin" id="not_admin" value="0"
                        {{ $user->is_admin == 0 ? 'checked' : '' }} />
                      <label class="form-check-label" for="not_admin">
                        User </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <a href="/user" class="btn btn-secondary me-1">Batal</a>
                <button type="button" class="btn btn-primary" onclick="editConfirmation()">Edit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script>
    function editConfirmation() {
      Swal.fire({
        title: "Yakin ingin mengubah data user?",
        text: "Aksi ini tidak bisa mengembalikan data!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2980B9",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update it!"
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('editForm').submit();
        }
      });
    };
  </script>
@endsection
