<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Tampil Semua (GET)
    public function index()
    {
        $books = Book::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $books
        ], 200);
    }

    // Tambah Data (POST) -> Return 201 Created
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'year' => 'required|integer',
            'stock' => 'required|integer|min:0',
        ]);

        $book = Book::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil ditambahkan',
            'data' => $book
        ], 201);
    }

    // Tampil Satu Data (GET {id})
    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $book
        ], 200);
    }

    // Ubah Data (PUT/PATCH {id})
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'year' => 'sometimes|required|integer',
            'stock' => 'sometimes|required|integer|min:0',
        ]);

        $book->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil diperbarui',
            'data' => $book
        ], 200);
    }

    // Hapus Data (DELETE {id})
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dihapus'
        ], 200);
    }
}