<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::paginate(15);
        return view('admin.colors.index', compact('colors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:colors',
            'code' => 'nullable|string|max:7'
        ]);

        Color::create($validated);

        return back()->with('success', 'Color created successfully');
    }

    public function destroy(Color $color)
    {
        $color->delete();
        return back()->with('success', 'Color deleted successfully');
    }
}
