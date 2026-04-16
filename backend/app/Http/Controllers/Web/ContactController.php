<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactGroup;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $contacts = $user->contacts()
            ->with('group')
            ->when($request->group_id, fn ($q, $g) => $q->where('contact_group_id', $g))
            ->when($request->search, fn ($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%");
            }))
            ->latest()
            ->paginate(25);

        $groups = $user->contactGroups()->get();

        return view('contacts.index', compact('contacts', 'groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'contact_group_id' => 'nullable|exists:contact_groups,id',
        ]);

        $request->user()->contacts()->create($request->only('name', 'phone', 'email', 'contact_group_id'));

        if ($request->contact_group_id) {
            ContactGroup::find($request->contact_group_id)?->updateContactsCount();
        }

        return redirect()->route('contacts.index')->with('success', 'Kontakt qo\'shildi');
    }

    public function update(Request $request, Contact $contact)
    {
        $this->authorize('update', $contact);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'contact_group_id' => 'nullable|exists:contact_groups,id',
        ]);

        $contact->update($request->only('name', 'phone', 'email', 'contact_group_id'));

        return redirect()->route('contacts.index')->with('success', 'Kontakt yangilandi');
    }

    public function destroy(Contact $contact)
    {
        $this->authorize('delete', $contact);
        $groupId = $contact->contact_group_id;
        $contact->delete();

        if ($groupId) {
            ContactGroup::find($groupId)?->updateContactsCount();
        }

        return redirect()->route('contacts.index')->with('success', 'Kontakt o\'chirildi');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
            'contact_group_id' => 'nullable|exists:contact_groups,id',
        ]);

        $file = $request->file('file');
        $rows = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_shift($rows);

        $nameIdx = array_search('name', array_map('strtolower', $header));
        $phoneIdx = array_search('phone', array_map('strtolower', $header));

        if ($nameIdx === false || $phoneIdx === false) {
            return back()->with('error', 'CSV faylda "name" va "phone" ustunlari bo\'lishi kerak');
        }

        $count = 0;
        foreach ($rows as $row) {
            if (!isset($row[$nameIdx], $row[$phoneIdx])) continue;
            $request->user()->contacts()->create([
                'name' => trim($row[$nameIdx]),
                'phone' => trim($row[$phoneIdx]),
                'contact_group_id' => $request->contact_group_id,
            ]);
            $count++;
        }

        if ($request->contact_group_id) {
            ContactGroup::find($request->contact_group_id)?->updateContactsCount();
        }

        return redirect()->route('contacts.index')->with('success', "{$count} ta kontakt import qilindi");
    }
}
