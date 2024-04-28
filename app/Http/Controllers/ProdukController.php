<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::latest()->get();

        return response()->json([
            'products' => $produk,
        ], 200);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'expired' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max size
        ]);

        // ini file masuk diluar public mungkin bisa dibilang privat || ini tanpa custom nama file
        // if ($request->hasFile('image')) {
        //     $imagePath = $request->file('image')->store('products');
        //     $validatedData['image'] = $imagePath;
        // }

        // ini file masuk ke public yang bisa di akses dengan URL || ini dengan custom nama file || pake uniqid
        if ($request->hasFile('image')) {
            // Create the product first to get the ID
            $product = Produk::create($validatedData);
            $imagePath = $request->file('image')->storeAs('products', $product->id . '_product_' . uniqid() . '.' . $request->image->extension(), ['disk' => 'public']);
            $product->image = $imagePath; // Update the image path in the product model
            $product->save(); // Save the product with the updated image path
        }

        // Return a response
        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    public function update($id, Request $request)
    {
        // Cari produk yang akan diubah
        $product = Produk::findOrFail($id);

        // Validate the incoming request data
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'expired' => 'required|date',
        ];
        
        if ($request->hasFile('image')) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120';
        }
        
        $validatedData = $request->validate($rules);        

        // Handle image update if provided
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            // Simpan gambar baru
            $imagePath = $request->file('image')->storeAs('products', $product->id . '_product_' . uniqid() . '.' . $request->image->extension(), ['disk' => 'public']);
            $validatedData['image'] = $imagePath; // Update the image path
        }

        // Update produk dengan data yang validasi
        $product->update($validatedData);

        // Return a response
        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully',
            'product' => $product
        ], 200);
    }

    public function destroy($id)
    {
        // Cari produk yang akan dihapus
        $product = Produk::findOrFail($id);

        // Hapus gambar jika ada
        if ($product->image) {
            // Storage::disk('public')->delete('products/' . $product->image); // ini lebih bagus
            Storage::disk('public')->delete($product->image); // ini kurang bagus
        }

        // Hapus produk
        $product->delete();

        // Return a response
        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully',
            'product' => $product
        ], 200);
    }

}
