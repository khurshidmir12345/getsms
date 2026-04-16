<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $devices = $request->user()->devices()->latest()->get();
        return view('devices.index', compact('devices'));
    }

    public function toggleActive(Device $device)
    {
        $device->update(['is_active' => !$device->is_active]);
        return back()->with('success', $device->is_active ? 'Qurilma faollashtirildi' : 'Qurilma o\'chirildi');
    }

    public function destroy(Device $device)
    {
        $device->delete();
        return redirect()->route('devices.index')->with('success', 'Qurilma o\'chirildi');
    }
}
