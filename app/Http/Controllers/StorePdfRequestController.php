<?php

namespace App\Http\Controllers;

use App\Models\BookPdfRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class StorePdfRequestController extends Controller
{
    public function index(Request $request)
    {
        $storeId = $request->user()->id;
        $statusFilter = $request->query('status', 'pending');

        if (!Schema::hasTable('book_pdf_requests')) {
            return view('store.pdf_requests', [
                'requests' => collect(),
                'statusFilter' => $statusFilter,
                'pendingPdfRequestsCount' => 0,
            ])->with('error', 'PDF requests are not available yet. Please run migrations.');
        }

        $query = BookPdfRequest::query()
            ->with(['book', 'customer'])
            ->where('store_id', $storeId);

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $requests = $query->latest('id')->get();
        $pendingCount = BookPdfRequest::query()
            ->where('store_id', $storeId)
            ->where('status', 'pending')
            ->count();

        return view('store.pdf_requests', [
            'requests' => $requests,
            'statusFilter' => $statusFilter,
            'pendingPdfRequestsCount' => $pendingCount,
        ]);
    }

    public function approve(Request $request, BookPdfRequest $pdfRequest)
    {
        if (!Schema::hasTable('book_pdf_requests')) {
            return redirect()
                ->back()
                ->with('error', 'PDF requests are not available yet. Please run migrations.');
        }

        if ($pdfRequest->store_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }

        if (!$pdfRequest->book || !$pdfRequest->book->pdf_path) {
            return redirect()
                ->back()
                ->with('error', 'This book does not have a PDF uploaded yet.');
        }

        $pdfRequest->update([
            'status' => 'approved',
            'approved_at' => now(),
            'rejected_at' => null,
        ]);

        return redirect()
            ->back()
            ->with('messages', ['PDF request approved.']);
    }

    public function reject(Request $request, BookPdfRequest $pdfRequest)
    {
        if (!Schema::hasTable('book_pdf_requests')) {
            return redirect()
                ->back()
                ->with('error', 'PDF requests are not available yet. Please run migrations.');
        }

        if ($pdfRequest->store_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }

        $pdfRequest->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'approved_at' => null,
        ]);

        return redirect()
            ->back()
            ->with('messages', ['PDF request declined.']);
    }
}
