<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    /**
     * System-wide dashboard
     */
    public function index(Request $request)
    {
        $stats = [
            'users_total' => User::count(),
            'users_active' => User::where('is_active', true)->count(),
            'users_new_today' => User::whereDate('created_at', today())->count(),
            'devices_total' => Device::count(),
            'devices_online' => Device::where('status', 'online')->count(),
            'messages_total' => Message::count(),
            'messages_today' => Message::whereDate('created_at', today())->count(),
            'messages_delivered' => Message::where('status', 'delivered')->count(),
            'messages_failed' => Message::where('status', 'failed')->count(),
            'messages_pending' => Message::whereIn('status', ['pending', 'queued', 'sending'])->count(),
        ];

        // Top 5 active users (by SMS count)
        $topUsers = User::withCount('messages')
            ->orderByDesc('messages_count')
            ->limit(5)
            ->get();

        // Recent users
        $recentUsers = User::latest()->limit(5)->get();

        // Last 7 days SMS chart
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartData[] = [
                'date' => $date->format('d.m'),
                'count' => Message::whereDate('created_at', $date)->count(),
            ];
        }

        return view('admin.dashboard', compact('stats', 'topUsers', 'recentUsers', 'chartData'));
    }

    /**
     * All users list
     */
    public function userIndex(Request $request)
    {
        $users = User::withCount(['devices', 'messages'])
            ->when($request->search, fn($q, $s) => $q->where(function($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            }))
            ->when($request->status === 'active', fn($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn($q) => $q->where('is_active', false))
            ->when($request->role, fn($q, $r) => $q->where('role', $r))
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Single user detail
     */
    public function userShow(User $user)
    {
        $user->loadCount(['devices', 'messages', 'contacts', 'templates', 'campaigns']);

        $stats = [
            'messages_delivered' => $user->messages()->where('status', 'delivered')->count(),
            'messages_failed' => $user->messages()->where('status', 'failed')->count(),
            'messages_pending' => $user->messages()->whereIn('status', ['pending', 'queued', 'sending'])->count(),
        ];

        $devices = $user->devices()->get();
        $recentMessages = $user->messages()->latest()->limit(10)->get();

        return view('admin.users.show', compact('user', 'stats', 'devices', 'recentMessages'));
    }

    /**
     * Update user (sms_limit, is_active, role, plan)
     */
    public function userUpdate(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'sms_limit' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
            'role' => 'required|in:user,super_admin',
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'sms_limit', 'is_active', 'role']));

        return redirect()->route('admin.users.show', $user)->with('success', 'Foydalanuvchi yangilandi');
    }

    /**
     * Impersonate user (login as them)
     */
    public function userImpersonate(Request $request, User $user)
    {
        $admin = Auth::user();

        if (!$admin->isSuperAdmin()) {
            abort(403);
        }

        // Save admin id for restoring later
        session(['impersonator_id' => $admin->id]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', "Siz {$user->name} sifatida kirdingiz");
    }

    /**
     * Stop impersonating
     */
    public function stopImpersonate(Request $request)
    {
        $impersonatorId = session('impersonator_id');

        if (!$impersonatorId) {
            return redirect()->route('dashboard');
        }

        $admin = User::find($impersonatorId);
        if (!$admin) {
            return redirect()->route('dashboard');
        }

        Auth::login($admin);
        session()->forget('impersonator_id');

        return redirect()->route('admin.dashboard')->with('success', 'Admin sifatida qaytdingiz');
    }

    /**
     * All devices across system
     */
    public function deviceIndex(Request $request)
    {
        $devices = Device::with('user')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->search, fn($q, $s) => $q->where(function($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('phone_number', 'like', "%{$s}%")
                  ->orWhere('model', 'like', "%{$s}%");
            }))
            ->latest()
            ->paginate(25);

        return view('admin.devices.index', compact('devices'));
    }

    /**
     * All messages across system
     */
    public function messageIndex(Request $request)
    {
        $messages = Message::with(['user', 'device'])
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->direction, fn($q, $d) => $q->where('direction', $d))
            ->when($request->user_id, fn($q, $u) => $q->where('user_id', $u))
            ->when($request->search, fn($q, $s) => $q->where(function($q) use ($s) {
                $q->where('phone_to', 'like', "%{$s}%")
                  ->orWhere('phone_from', 'like', "%{$s}%")
                  ->orWhere('body', 'like', "%{$s}%");
            }))
            ->latest()
            ->paginate(30);

        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Delete user
     */
    public function userDestroy(User $user)
    {
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Super Admin foydalanuvchisini o\'chirib bo\'lmaydi');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Foydalanuvchi o\'chirildi');
    }
}
