@extends('layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Stock</h1>
    <nav>
      <ol class="breadcrumb">
        {{-- <li class="breadcrumb-item"><a href="/dashboard/admin">Admin</a></li> --}}
        <li class="breadcrumb-item active"><a href="/stock">Stock</a></li>
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
                <h5 class="card-title">Stock</h5>
              </div>
              <div>
                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                  data-bs-target="#addNewStockModal">Baru</button>
              </div>
            </div>

            <!-- Table with stripped rows -->
            <div class="table table-responsive">
              <table class="table" id="stock-table">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Tanggal Buat</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($products->isNotEmpty())
                    @foreach ($products as $product)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category }}</td>
                        <td>Rp. {{ round($product->price, 2) }}</td>
                        <td>{{ $product->stock }}</td>
                        <td class="text-center align-middle" style="padding: 0;">
                          <span
                            style="color:#219653; background-color: #e8f4ed; border-radius: 10px; padding: 3px 5px; display: inline-block; box-sizing: border-box">
                            {{ \Carbon\Carbon::parse($product->created_at)->format('d-m-Y H:i:s') }}
                          </span>
                        </td>
                        <td>
                          <button type="button" class="btn btn-warning" id="btn-edit" data-edit-id="{{ $product->id }}"
                            data-bs-toggle="modal" data-bs-target="#editStockModal"><i class="bi bi-pencil"></i></button>
                          <form action="/products/{{ $product->id }}" method="POST" class="d-inline delete-form"
                            id="deleteForm{{ $product->id }}">
                            @method('DELETE')
                            @csrf
                            <button type="button" class="btn btn-danger"
                              onclick="deleteConfirmation('{{ $product->id }}')">
                              <i class="bi bi-trash"></i>
                            </button>
                          </form>
                        </td>
                      </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="7" class="text-center">No transactions found for the selected date range.</td>
                    </tr>
                  @endif
                </tbody>
              </table>
              <div class="d-flex justify-content-center">
                {{ $products->links() }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="addNewStockModal" tabindex="-1" aria-labelledby="newStockLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Produk Baru</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="/products" method="post" id="addStockForm">
            <div class="modal-body">
              <div class="mb-3">
                <label for="name" class="form-label @error('name') is-invalid @enderror">Nama
                  Product Baru</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                  required autofocus>
                @error('name')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="price" class="form-label">Kategori Produk</label>
                <select class="form-select" name="category" aria-label="Default select example">
                  <option selected>Open this select menu</option>
                  <option value="Elektronik">Elektronik</option>
                  <option value="Buku">Buku</option>
                  <option value="Pakaian">Pakaian</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="price" class="form-label">Harga</label>
                <div class="input-group">
                  <span class="input-group-text flex-nowrap" id="addon-wrapping"
                    style="border-radius: 5px 0 0 5px">Rp.</span>
                  <input type="text" inputmode="numeric" class="form-control" id="price" name="price"
                    value="{{ old('price') }}" required autofocus>
                  @error('price')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="mb-3">
                <label for="stock" class="form-label">Jumlah Produk</label>
                <input type="text" inputmode="numeric" class="form-control" id="stock" name="stock"
                  value="{{ old('stock') }}" required autofocus>
                @error('stock')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editStockModal" tabindex="-1" aria-labelledby="editStockLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Produk</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="/products/" method="post" id="editStockForm">
            @csrf
            @method('PUT')
            <div class="modal-body">
              <input type="text" class="form-control" id="edit-id" name="id" required hidden>
              <div class="mb-3">
                <label for="name" class="form-label @error('name') is-invalid @enderror">Nama
                  Produk</label>
                <input type="text" class="form-control" id="edit-name" name="name" required>
                @error('name')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="edit-category" class="form-label @error('category') is-invalid @enderror">Kategori
                  Produk</label>
                <select class="form-select" name="category" aria-label="Default select example">
                  <option selected>Open this select menu</option>
                  <option value="Elektronik">Elektronik</option>
                  <option value="Buku">Buku</option>
                  <option value="Pakaian">Pakaian</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="edit-price" class="form-label @error('price') is-invalid @enderror">Harga</label>
                <div class="input-group">
                  <span class="input-group-text flex-nowrap" id="addon-wrapping"
                    style="border-radius: 5px 0 0 5px">Rp.</span>
                  <input type="text" inputmode="numeric" class="form-control" id="edit-price" name="price"
                    value="{{ old('price') }}" required autofocus>
                  @error('price')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                @error('price')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="edit-stock" class="form-label @error('stock') is-invalid @enderror">Jumlah
                  Produk</label>
                <input type="text" inputmode="numeric" class="form-control" id="edit-stock" name="stock"
                  required>
                @error('stock')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="btn-update-"
                onclick="editConfirmation()">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <script>
    $(document).ready(function() {
      $('#addStockForm').on('submit', function(e) {
        e.preventDefault();

        var form = $(this);
        var url = form.attr('action');
        var formData = form.serialize();

        $.ajax({
          url: url,
          type: 'POST',
          data: formData,
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(response) {
            $('#addNewStockModal').modal('hide');
            $('#addStockForm')[0].reset();

            setTimeout(function() {
              $('.modal-backdrop').remove();
              $('body').removeClass('modal-open');
              $('body').css({
                'overflow': '',
                'padding-right': ''
              });
              $('#addNewStockModal').removeClass('show').attr('aria-modal', null).css('display',
                'none');
            }, 500);

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
      });

      $(document).on('click', '#btn-edit', function() {
        var productId = $(this).data('edit-id');

        // Ambil data stock via AJAX
        $.ajax({
          url: '/products/' + productId + '/edit',
          type: 'GET',
          success: function(data) {
            $('#edit-id').val(data.id);
            $('#editStockForm').attr('action', '/products/' + data.id);
            $('#editStockForm input[name="id"]').val(data.id);
            $('#editStockForm input[name="name"]').val(data.name);
            $('#editStockForm select[name="category"]').val(data.category).trigger('change');
            $('#editStockForm input[name="price"]').val(Math.round(data.price));
            $('#editStockForm input[name="stock"]').val(data.stock);
            $('#btn-update-').attr('id', 'btn-update-' + data.id);

            // Tampilkan modal edit
            $('#editStockModal').modal('show');
          },
          error: function(xhr) {
            alert('Gagal mengambil data product.');
          }
        });
      });

      $(document).on('click', '#btn-request-approval', function() {
        var stockId = $(this).data('stock-id');
        console.log("Request Approval for stock ID: " + stockId);

        // Ambil data stock via AJAX
        $.ajax({
          url: '/getRequestData',
          type: 'GET',
          data: {
            id: stockId
          },
          success: function(data) {
            // $('#request-approval-id').val(data.id);
            // $('#request-approval-id').attr('action', '/stock/' + data.id);
            $('#requestApprovalForm input[name="stock_id"]').val(data.id);
            $('#requestApprovalForm input[name="name"]').val(data.name);
            // $('#requestApprovalForm input[name="amount"]').val(data.amount);
            $('#btn-request-approval-').attr('id', 'btn-request-approval-' + data.id);

            // Tampilkan modal edit
            $('#requestApprovalModal').modal('show');
            console.log("Data received for request approval:", data);

            console.log("ID:", $('#request-approval-id').val());
            console.log("Name:", $('#request-approval-name').val());
          },
          error: function(xhr) {
            alert('Gagal mengambil data stock.');
          }
        });
      });

      // Reset button functionality (updated)
      // $('#tombolReset').on('click', function() {
      //   table.search('').columns().search('').draw();
      // });
    });

    function refreshStockTable() {
      $.ajax({
        url: '/products', // Pastikan route ini mengembalikan partial view atau JSON data
        type: 'GET',
        dataType: 'html',
        success: function(data) {
          // Ambil tbody baru dari response dan replace tbody lama
          var newTbody = $(data).find('#stock-table tbody').html();
          $('#stock-table tbody').html(newTbody);
        }
      });
    }

    function submitEditForm() {
      var form = $('#editStockForm');
      var url = form.attr('action');
      var formData = form.serialize();

      $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        // success: function(response) {
        //   console.log("success")
        // },
        // error: function(xhr) {
        //   console.log("error")
        // }
        success: function(response) {
          $('#editStockModal').modal('hide');
          $('#editStockForm')[0].reset();

          setTimeout(function() {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            $('body').css({
              'overflow': '',
              'padding-right': ''
            });
            $('#editStockModal').removeClass('show').attr('aria-modal', null).css('display',
              'none');
          }, 500);

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

    function editConfirmation() {
      Swal.fire({
        title: "Yakin ingin meng-edit data stock?",
        text: "Aksi ini tidak bisa mengembalikan data!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2980B9",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update it!"
      }).then((result) => {
        if (result.isConfirmed) {
          console.log("Edit Confirmed. Trying to edit stock data...");
          // document.getElementById('editStockForm').submit();
          submitEditForm();
        }
      });
    }

    function deleteConfirmation(productId) {
      Swal.fire({
        title: "Yakin ingin menghapus data produk?",
        text: "Aksi ini tidak bisa mengembalikan data!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2980B9",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then((result) => {
        if (result.isConfirmed) {
          console.log("Delete Confirmed. Trying to delete product...");
          // document.getElementById('deleteForm' + productId).submit();
          // $(`#deleteForm${productId}`).submit();
          submitDeleteForm(productId);
        }
      });
    }

    function submitDeleteForm(stockId) {
      var form = $(`#deleteForm${stockId}`);
      var url = form.attr('action');
      var formData = form.serialize();

      $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        success: function(response) {
          console.log("Delete stock data demo success")

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
