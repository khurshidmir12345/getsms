<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\SmsService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $messages = $user->messages()
            ->with('device', 'contact')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->direction, fn ($q, $d) => $q->where('direction', $d))
            ->when($request->search, fn ($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('phone_to', 'like', "%{$s}%")
                    ->orWhere('body', 'like', "%{$s}%");
            }))
            ->latest()
            ->paginate(25);

        return view('messages.index', compact('messages'));
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $devices = $user->devices()->where('is_active', true)->get();
        $contacts = $user->contacts()->get();
        $templates = $user->templates()->where('is_active', true)->get();

        return view('messages.create', compact('devices', 'contacts', 'templates'));
    }

    public function store(Request $request, SmsService $smsService)
    {
        $request->validate([
            'phone_to' => 'required|string|max:20',
            'body' => 'required|string|max:1600',
            'device_id' => 'nullable|integer',
        ]);

        try {
            $smsService->send(
                $request->user(),
                $request->phone_to,
                $request->body,
                $request->device_id
            );
            return redirect()->route('messages.index')->with('success', 'SMS navbatga qo\'shildi');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
