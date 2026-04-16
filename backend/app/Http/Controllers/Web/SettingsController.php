<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        return view('settings.index', compact('user'));
    }

    public function regenerateApiKey(Request $request)
    {
        $request->user()->update([
            'api_key' => 'sk_' . Str::random(40),
            'api_secret' => Str::random(48),
        ]);

        return back()->with('success', 'API kalit yangilandi');
    }
}
