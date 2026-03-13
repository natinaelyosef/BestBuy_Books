<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\IssueReport;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
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

        // Return Blade view, not Inertia
        return view('admin.dashboard', [
            'openReports' => $openReports,
            'openChats' => $openChats,
            'adminCount' => $adminCount,
            'user' => $request->user(),
        ]);
    }
}