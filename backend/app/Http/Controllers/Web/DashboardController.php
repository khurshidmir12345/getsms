<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total_messages' => $user->messages()->count(),
            'sent_today' => $user->messages()->where('direction', 'outgoing')->whereDate('created_at', today())->count(),
            'delivered' => $user->messages()->where('status', 'delivered')->count(),
            'failed' => $user->messages()->where('status', 'failed')->count(),
            'pending' => $user->messages()->where('status', 'pending')->count(),
            'devices_online' => $user->devices()->where('status', 'online')->count(),
            'devices_total' => $user->devices()->count(),
            'contacts_total' => $user->contacts()->count(),
            'sms_used' => $user->sms_used,
            'sms_limit' => $user->sms_limit,
        ];

        // Last 7 days chart data
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartData[] = [
                'date' => $date->format('d M'),
                'sent' => $user->messages()->where('direction', 'outgoing')->whereDate('created_at', $date)->count(),
                'received' => $user->messages()->where('direction', 'incoming')->whereDate('created_at', $date)->count(),
            ];
        }

        $recentMessages = $user->messages()
            ->with('device', 'contact')
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', compact('stats', 'chartData', 'recentMessages'));
    }
}
