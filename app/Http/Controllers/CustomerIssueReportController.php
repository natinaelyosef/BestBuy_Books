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
        ]);

        IssueReport::create([
            'user_id' => $request->user()->id,
            'subject' => $data['subject'],
            'description' => $data['description'],
            'priority' => $data['priority'],
            'status' => 'open',
        ]);

        return redirect()
            ->route('issue-reports.index')
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
