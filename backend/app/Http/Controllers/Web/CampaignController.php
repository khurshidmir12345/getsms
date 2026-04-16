<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Services\SmsService;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $campaigns = $request->user()->campaigns()
            ->with('template', 'contactGroup', 'device')
            ->latest()
            ->paginate(25);

        return view('campaigns.index', compact('campaigns'));
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $templates = $user->templates()->where('is_active', true)->get();
        $groups = $user->contactGroups()->get();
        $devices = $user->devices()->where('is_active', true)->get();

        return view('campaigns.create', compact('templates', 'groups', 'devices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'template_id' => 'required|exists:templates,id',
            'contact_group_id' => 'required|exists:contact_groups,id',
            'device_id' => 'nullable|exists:devices,id',
            'rate_limit' => 'nullable|integer|min:1|max:60',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $user = $request->user();
        $group = $user->contactGroups()->findOrFail($request->contact_group_id);

        $campaign = $user->campaigns()->create([
            'name' => $request->name,
            'template_id' => $request->template_id,
            'contact_group_id' => $request->contact_group_id,
            'device_id' => $request->device_id,
            'total_messages' => $group->contacts()->count(),
            'rate_limit' => $request->rate_limit ?? 20,
            'scheduled_at' => $request->scheduled_at,
            'status' => $request->scheduled_at ? 'draft' : 'draft',
        ]);

        return redirect()->route('campaigns.index')->with('success', 'Kampaniya yaratildi');
    }

    public function start(Request $request, Campaign $campaign, SmsService $smsService)
    {
        if ($campaign->status !== 'draft' && $campaign->status !== 'paused') {
            return back()->with('error', 'Kampaniyani boshlash mumkin emas');
        }

        $user = $request->user();
        $template = $campaign->template;
        $contacts = $campaign->contactGroup->contacts;

        $campaign->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        foreach ($contacts as $contact) {
            $body = $template->render([
                'name' => $contact->name,
                'phone' => $contact->phone,
            ]);

            try {
                $smsService->send(
                    $user, $contact->phone, $body,
                    $campaign->device_id, $contact->id, $campaign->id
                );
            } catch (\Exception $e) {
                break;
            }
        }

        return redirect()->route('campaigns.index')->with('success', 'Kampaniya boshlandi');
    }

    public function pause(Campaign $campaign)
    {
        $campaign->update(['status' => 'paused']);
        return back()->with('success', 'Kampaniya to\'xtatildi');
    }

    public function cancel(Campaign $campaign)
    {
        $campaign->update(['status' => 'cancelled']);
        $campaign->messages()->where('status', 'pending')->update(['status' => 'failed', 'error_message' => 'Campaign cancelled']);
        return back()->with('success', 'Kampaniya bekor qilindi');
    }
}
