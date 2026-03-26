<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Section;
use App\Models\ContentEntry;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'clients_count' => Client::count(),
            'active_clients' => Client::where('status', 'active')->count(),
            'sections_count' => Section::count(),
            'content_entries' => ContentEntry::count(),
            'users_count' => User::count(),
        ];
        
        $recentClients = Client::latest()->take(5)->get();
        
        return view('admin.dashboard', compact('stats', 'recentClients'));
    }
}
