<?php

namespace App\Http\Controllers;

use App\Models\IssueReport;
use Illuminate\Http\Request;

class CustomerIssueReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = IssueReport::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('customer.issue_reports.index', [
            'reports' => $reports,
        ]);
    }

    public function create()
    {
        return view('customer.issue_reports.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:4000',
            'priority' => 'required|in:low,medium,high',
            'evidence' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt',
        ]);

        $reportData = [
            'user_id' => $request->user()->id,
            'reporter_role' => 'customer',
            'subject' => $data['subject'],
            'description' => $data['description'],
            'priority' => $data['priority'],
            'status' => 'open',
        ];

        if ($request->hasFile('evidence')) {
            $file = $request->file('evidence');
            $path = $file->store('report-evidence', 'public');
            $reportData['evidence_path'] = $path;
            $reportData['evidence_name'] = $file->getClientOriginalName();
            $reportData['evidence_type'] = $file->getMimeType();
        }

        IssueReport::create($reportData);

        return redirect()
            ->route('customer.issue-reports.index')
            ->with('status', 'Issue report submitted.');
    }

    public function show(Request $request, IssueReport $issueReport)
    {
        if ((int) $issueReport->user_id !== (int) $request->user()->id) {
            abort(403);
        }

        return view('customer.issue_reports.show', [
            'report' => $issueReport,
        ]);
    }
}
