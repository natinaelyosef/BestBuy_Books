<?php

namespace App\Http\Controllers;

use App\Models\IssueReport;
use App\Models\User;
use Illuminate\Http\Request;

class AdminIssueReportController extends Controller
{
    public function index(Request $request)
    {
        $query = IssueReport::with(['user', 'reportedUser', 'assignedAdmin']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $reports = $query->latest()->get();

        return view('admin.issue_reports.index', compact('reports'));
    }

    public function show(IssueReport $issueReport)
    {
        $issueReport->load(['user', 'reportedUser', 'assignedAdmin']);
        $admins = User::whereIn('account_type', ['sub_admin', 'super_admin'])
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
            'status'           => 'required|in:open,in_review,resolved,closed',
            'assigned_admin_id'=> 'nullable|exists:users,id',
            'admin_notes'      => 'nullable|string|max:2000',
        ]);

        $issueReport->update([
            'status'            => $data['status'],
            'assigned_admin_id' => $data['assigned_admin_id'] ?? $issueReport->assigned_admin_id,
            'admin_notes'       => $data['admin_notes'] ?? $issueReport->admin_notes,
        ]);

        return redirect()
            ->route('admin.issue-reports.show', $issueReport)
            ->with('status', 'Issue report updated.');
    }

    /**
     * Ban the reported user from the report page.
     */
    public function banUser(Request $request, IssueReport $issueReport)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $target = $issueReport->reportedUser ?? $issueReport->user;
        if (!$target) {
            return redirect()->back()->with('error', 'No user found to ban.');
        }

        $target->update([
            'is_banned'  => true,
            'ban_reason' => $request->reason,
            'banned_at'  => now(),
        ]);

        $issueReport->update(['status' => 'resolved', 'admin_notes' => "User banned: {$request->reason}"]);

        return redirect()->route('admin.issue-reports.show', $issueReport)
            ->with('status', "{$target->name} has been banned.");
    }

    /**
     * Warn the reported user.
     */
    public function warnUser(IssueReport $issueReport)
    {
        $target = $issueReport->reportedUser ?? $issueReport->user;
        if (!$target) {
            return redirect()->back()->with('error', 'No user found to warn.');
        }

        $target->increment('warning_count');
        $issueReport->update(['admin_notes' => ($issueReport->admin_notes ?? '') . "\nWarning #{$target->warning_count} issued."]);

        return redirect()->route('admin.issue-reports.show', $issueReport)
            ->with('status', "Warning issued to {$target->name}. Total warnings: {$target->warning_count}.");
    }

    /**
     * Restrict the reported user for N days.
     */
    public function restrictUser(Request $request, IssueReport $issueReport)
    {
        $request->validate(['days' => 'required|integer|min:1|max:365']);

        $target = $issueReport->reportedUser ?? $issueReport->user;
        if (!$target) {
            return redirect()->back()->with('error', 'No user found to restrict.');
        }

        $target->update([
            'is_restricted'    => true,
            'restricted_until' => now()->addDays($request->days),
        ]);

        $issueReport->update(['status' => 'in_review']);

        return redirect()->route('admin.issue-reports.show', $issueReport)
            ->with('status', "{$target->name} has been restricted for {$request->days} day(s).");
    }

    /**
     * Mark issue as resolved/finished.
     */
    public function resolve(IssueReport $issueReport)
    {
        $issueReport->update(['status' => 'resolved']);
        return redirect()->route('admin.issue-reports.show', $issueReport)
            ->with('status', 'Issue marked as resolved.');
    }
}