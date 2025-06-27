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
    Log::info('Trying to edit stock: ', ['id' => $product->id, 'name' => $product->name, 'category' => $product->category, 'price' => $product->price, 'stock' => $product->stock]);

    return response()->json([
      'id' => $product->id,
      'name' => $product->name,
      'category' => $product->category,
      'price' => $product->price,
      'stock' => $product->stock,
    ]);
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
    try {
      $rules = [];

      if ($request->name !== $product->name) {
        $rules['name'] = 'required|string|max:255';
      }

      if ($request->category !== $product->category) {
        $rules['category'] = 'required|string|max:255';
      }

      if ($request->price !== $product->price) {
        $rules['price'] = 'required|integer|min:1';
      }

      if ($request->stock !== $product->stock) {
        $rules['stock'] = 'required|integer|min:1';
      }

      $validatedData = $request->validate($rules);
      $product->update($validatedData);

      Log::info('Product updated with data: ', $product->toArray());
      if ($request->ajax()) {
        return response()->json(['success' => true, 'message' => 'Produk berhasil diupdate!']);
      }
    } catch (\Exception $e) {
      Log::error('Error updating produk: ' . $e->getMessage());

      if ($request->ajax()) {
        return response()->json(['success' => false, 'message' => 'Gagal memperbarui produk.'], 422);
      }

      return redirect()->back()->with('error', 'Gagal memperbarui produk.');
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request, Product $product)
  {
    try {
      Log::info('Trying to delete stock.');
      $product->delete();
      Log::info('Product deleted: ', ['id' => $product->id, 'name' => $product->name]);

      if ($request->ajax()) {
        return response()->json(['success' => true, 'message' => 'Stock berhasil dihapus!']);
      }

      return response()->json(['success' => true, 'message' => 'Stock berhasil dihapus!']);
    } catch (\Exception $e) {
      Log::error('Error deleting stock: ' . $e->getMessage());

      return response()->json(['success' => false, 'message' => 'Gagal menghapus stock.'], 422);
    }
  }
}
