<?php

namespace App\Http\Controllers;

use App\Models\IssueReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreIssueReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = IssueReport::with(['reportedUser'])
            ->where('user_id', $request->user()->id)
            ->where('reporter_role', 'store_owner')
            ->latest()
            ->get();

        return view('store.issue_reports.index', compact('reports'));
    }

    public function create()
    {
        // Get customers who have had conversations with this store
        $customers = User::where('account_type', 'customer')
            ->orderBy('name')
            ->get();

        return view('store.issue_reports.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reported_user_id' => 'required|exists:users,id',
            'subject'          => 'required|string|max:255',
            'description'      => 'required|string|max:4000',
            'priority'         => 'required|in:low,medium,high',
            'evidence'         => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt',
        ]);

        $reportData = [
            'user_id'          => $request->user()->id,
            'reported_user_id' => $data['reported_user_id'],
            'reporter_role'    => 'store_owner',
            'subject'          => $data['subject'],
            'description'      => $data['description'],
            'priority'         => $data['priority'],
            'status'           => 'open',
        ];

        if ($request->hasFile('evidence')) {
            $file = $request->file('evidence');
            $path = $file->store('report-evidence', 'public');
            $reportData['evidence_path'] = $path;
            $reportData['evidence_name'] = $file->getClientOriginalName();
            $reportData['evidence_type'] = $file->getMimeType();
        }

        IssueReport::create($reportData);

        return redirect()->route('store.issue-reports.index')
            ->with('status', 'Report submitted successfully. An admin will review it shortly.');
    }

    public function show(Request $request, IssueReport $issueReport)
    {
        if ((int) $issueReport->user_id !== (int) $request->user()->id) {
            abort(403);
        }

        $issueReport->load(['reportedUser', 'assignedAdmin']);

        return view('store.issue_reports.show', compact('issueReport'));
    }
}
