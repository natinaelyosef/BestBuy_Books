<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookPdfRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomerPdfController extends Controller
{
    public function index(Request $request)
    {
        $customer = $request->user();

        if (!$this->hasPdfRequestsTable()) {
            return view('customer.pdfs', [
                'requests' => collect(),
                'approvedRequests' => collect(),
                'pendingRequests' => collect(),
                'rejectedRequests' => collect(),
            ])->with('error', 'PDF requests are not available yet. Please run migrations.');
        }

        $requests = BookPdfRequest::query()
            ->with(['book', 'store'])
            ->where('customer_id', $customer->id)
            ->latest('id')
            ->get();

        return view('customer.pdfs', [
            'requests' => $requests,
            'approvedRequests' => $requests->where('status', 'approved'),
            'pendingRequests' => $requests->where('status', 'pending'),
            'rejectedRequests' => $requests->where('status', 'rejected'),
        ]);
    }

    public function storeRequest(Request $request, Book $book)
    {
        if (!$this->hasPdfRequestsTable()) {
            return redirect()
                ->back()
                ->with('error', 'PDF requests are not available yet. Please run migrations.');
        }

        $customer = $request->user();

        if (!$book->pdf_path) {
            return redirect()
                ->back()
                ->with('error', 'This book does not have a PDF available.');
        }

        $existing = BookPdfRequest::query()
            ->where('book_id', $book->id)
            ->where('customer_id', $customer->id)
            ->first();

        if ($existing) {
            if ($existing->status === 'approved') {
                return redirect()
                    ->back()
                    ->with('status', 'Your PDF request was already approved.');
            }

            if ($existing->status === 'pending') {
                return redirect()
                    ->back()
                    ->with('status', 'Your PDF request is already pending.');
            }

            $existing->update([
                'status' => 'pending',
                'approved_at' => null,
                'rejected_at' => null,
            ]);

            return redirect()
                ->back()
                ->with('status', 'Your PDF request has been sent again.');
        }

        BookPdfRequest::create([
            'book_id' => $book->id,
            'customer_id' => $customer->id,
            'store_id' => $book->user_id,
            'status' => 'pending',
        ]);

        return redirect()
            ->back()
            ->with('status', 'Your PDF request was sent to the store owner.');
    }

    public function download(Request $request, BookPdfRequest $pdfRequest)
    {
        if (!$this->hasPdfRequestsTable()) {
            return redirect()
                ->back()
                ->with('error', 'PDF requests are not available yet. Please run migrations.');
        }

        $customer = $request->user();

        if ($pdfRequest->customer_id !== $customer->id) {
            abort(403, 'Unauthorized');
        }

        if ($pdfRequest->status !== 'approved') {
            return redirect()
                ->back()
                ->with('error', 'This PDF request has not been approved yet.');
        }

        $book = $pdfRequest->book;
        if (!$book || !$book->pdf_path) {
            return redirect()
                ->back()
                ->with('error', 'The PDF file is no longer available.');
        }

        if (!Storage::disk('local')->exists($book->pdf_path)) {
            return redirect()
                ->back()
                ->with('error', 'The PDF file could not be found.');
        }

        $filename = $book->pdf_name ?: (Str::slug($book->title) . '.pdf');

        return Storage::disk('local')->download($book->pdf_path, $filename);
    }

    private function hasPdfRequestsTable(): bool
    {
        return Schema::hasTable('book_pdf_requests');
    }
}
