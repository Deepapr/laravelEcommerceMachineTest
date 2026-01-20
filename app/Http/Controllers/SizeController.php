<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::paginate(15);
        return view('admin.sizes.index', compact('sizes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sizes',
            'value' => 'nullable|string|max:100'
        ]);

        Size::create($validated);

        return back()->with('success', 'Size created successfully');
    }

    public function destroy(Size $size)
    {
        $size->delete();
        return back()->with('success', 'Size deleted successfully');
    }
}
