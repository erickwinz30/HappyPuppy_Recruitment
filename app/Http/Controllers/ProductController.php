<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $products = Product::latest()->paginate(10);

    return view('index', [
      'products' => $products,
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    try {
      $validatedData = $request->validate([
        'name' => 'required|max:255',
        'category' => 'required|max:255',
        'price' => 'required|numeric|min:1',
        'stock' => 'required|numeric|min:1',
      ]);

      Log::info('Storing stock with data: ', $validatedData);

      $newProduct = Product::create($validatedData);
      Log::info('New product created: ', ['id' => $newProduct->id]);

      if ($request->ajax()) {
        return response()->json(['success' => true, 'message' => 'Produk baru berhasil ditambahkan!.']);
      }
    } catch (\Exception $e) {
      Log::error('Error storing stock: ' . $e->getMessage());

      if ($request->ajax()) {
        $message = 'Gagal menambahkan stock baru.';
        $errors = [];

        // Jika error validasi, ambil pesan error validasi
        if ($e instanceof \Illuminate\Validation\ValidationException) {
          $message = 'Validasi gagal!';
          $errors = $e->errors();
        }

        return response()->json([
          'success' => false,
          'message' => $message,
          'errors' => $errors
        ], 422);
      }
    }

    // Product::create($validatedData);

    // return redirect('/')->with('success', 'Produk berhasil ditambahkan!');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function show(Product $product)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function edit(Product $product)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Product $product)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function destroy(Product $product)
  {
    //
  }
}
