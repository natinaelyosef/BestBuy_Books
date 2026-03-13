<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function create()
    {
        return view('store.books.add');
    }

    public function edit(Request $request, $id)
    {
        $book = Book::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return view('store.books.edit', compact('book'));
    }

    public function index(Request $request)
    {
        $books = Book::query()
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->get();

        $storeMetrics = [
            'total_books' => $books->count(),
        ];

        return view('store.books.inventory', compact('books', 'storeMetrics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'required|string|max:100',
            'publication_year' => 'nullable|integer|min:1000|max:' . now()->year,
            'total_copies' => 'required|integer|min:0',
            'available_rent' => 'required|integer|min:0',
            'available_sale' => 'required|integer|min:0',
            'rental_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        if (($validated['available_rent'] + $validated['available_sale']) > $validated['total_copies']) {
            return back()
                ->withErrors(['total_copies' => 'Total copies must be at least the sum of available rent and sale copies'])
                ->withInput();
        }

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $directory = public_path('uploads/books');
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $file = $request->file('cover_image');
            $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);
            $coverImagePath = 'uploads/books/' . $filename;
        }

        $payload = $validated;
        unset($payload['cover_image']);
        $payload['user_id'] = $request->user()->id;
        $payload['cover_image_path'] = $coverImagePath;

        Book::create($payload);

        $route = $request->has('save_and_add') ? 'add.book.registration' : 'view.inventory';

        return redirect()
            ->route($route)
            ->with('messages', ['Book saved successfully.']);
    }

    public function update(Request $request, $id)
    {
        $book = Book::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'required|string|max:100',
            'publication_year' => 'nullable|integer|min:1000|max:' . now()->year,
            'total_copies' => 'required|integer|min:0',
            'available_rent' => 'required|integer|min:0',
            'available_sale' => 'required|integer|min:0',
            'rental_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        if (($validated['available_rent'] + $validated['available_sale']) > $validated['total_copies']) {
            return back()
                ->withErrors(['total_copies' => 'Total copies must be at least the sum of available rent and sale copies'])
                ->withInput();
        }

        $coverImagePath = $book->cover_image_path;
        if ($request->hasFile('cover_image')) {
            $directory = public_path('uploads/books');
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            if ($coverImagePath) {
                $oldPath = public_path($coverImagePath);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file = $request->file('cover_image');
            $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);
            $coverImagePath = 'uploads/books/' . $filename;
        }

        $payload = $validated;
        unset($payload['cover_image']);
        $payload['cover_image_path'] = $coverImagePath;

        $book->update($payload);

        return redirect()
            ->route('view.inventory')
            ->with('messages', ['Book updated successfully.']);
    }

    public function destroy(Request $request, $id)
    {
        $book = Book::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        if ($book->cover_image_path) {
            $path = public_path($book->cover_image_path);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $book->delete();

        return redirect()
            ->route('view.inventory')
            ->with('messages', ['Book deleted successfully.']);
    }
}