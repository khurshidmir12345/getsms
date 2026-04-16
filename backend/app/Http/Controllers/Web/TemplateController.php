<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $templates = $request->user()->templates()->latest()->paginate(25);
        return view('templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'body' => 'required|string|max:1600',
            'category' => 'nullable|string|max:50',
        ]);

        $request->user()->templates()->create($request->only('name', 'body', 'category'));
        return redirect()->route('templates.index')->with('success', 'Shablon yaratildi');
    }

    public function update(Request $request, Template $template)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'body' => 'required|string|max:1600',
            'category' => 'nullable|string|max:50',
        ]);

        $template->update($request->only('name', 'body', 'category'));
        return redirect()->route('templates.index')->with('success', 'Shablon yangilandi');
    }

    public function destroy(Template $template)
    {
        $template->delete();
        return redirect()->route('templates.index')->with('success', 'Shablon o\'chirildi');
    }
}
