<?php

namespace App\Http\Controllers;

use App\Models\IssueReport;
use App\Models\User;
use Illuminate\Http\Request;

class AdminIssueReportController extends Controller
{
    public function index()
    {
        $reports = IssueReport::query()
            ->with(['user', 'assignedAdmin'])
            ->latest()
            ->get();

        return view('admin.issue_reports.index', ['reports' => $reports]);
    }

    public function show(IssueReport $issueReport)
    {
        $issueReport->load(['user', 'assignedAdmin']);
        $admins = User::query()
            ->whereIn('account_type', ['sub_admin', 'super_admin'])
            ->orderBy('name')
            ->get();

        return view('admin.issue_reports.show', [
            'report' => $issueReport,
            'admins' => $admins,
        ]);
    }

    public function update(Request $request, IssueReport $issueReport)
    {
        $data = $request->validate([
            'status' => 'required|in:open,in_review,resolved,closed',
            'assigned_admin_id' => 'nullable|exists:users,id',
        ]);

        $issueReport->update([
            'status' => $data['status'],
            'assigned_admin_id' => $data['assigned_admin_id'] ?? $issueReport->assigned_admin_id,
        ]);

        return redirect()
            ->route('admin.issue-reports.show', $issueReport)
            ->with('status', 'Issue report updated.');
    }
}