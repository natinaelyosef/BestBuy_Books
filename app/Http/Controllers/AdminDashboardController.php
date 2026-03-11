<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\IssueReport;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $openReports = IssueReport::query()
            ->where('status', 'open')
            ->count();

        $openChats = ChatConversation::query()
            ->where('status', 'open')
            ->count();

        $adminCount = User::query()
            ->whereIn('account_type', ['sub_admin', 'super_admin'])
            ->count();

        return view('admin.dashboard', [
            'openReports' => $openReports,
            'openChats' => $openChats,
            'adminCount' => $adminCount,
        ]);
    }
}
