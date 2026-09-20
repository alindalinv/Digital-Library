<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublisherController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:publishers.view')->only(['index', 'show']);
        $this->middleware('permission:publishers.create')->only(['create', 'store']);
        $this->middleware('permission:publishers.update')->only(['edit', 'update']);
        $this->middleware('permission:publishers.delete')->only(['destroy']);
    }

    public function index()
    {
        $publishers = Publisher::withCount('books')->latest()->paginate(10);
        return view('admin.publishers.index', compact('publishers'));
    }

    public function create()
    {
        return view('admin.publishers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255|unique:publishers,name',
            'email'   => 'nullable|email',
            'phone'   => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'website' => 'nullable|url',
            'status'  => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        Publisher::create($data);

        return redirect()->route('admin.publishers.index')
            ->with('success', 'Publisher created.');
    }

    public function edit(Publisher $publisher)
    {
        return view('admin.publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255|unique:publishers,name,' . $publisher->id,
            'email'   => 'nullable|email',
            'phone'   => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'website' => 'nullable|url',
            'status'  => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $publisher->update($data);

        return redirect()->route('admin.publishers.index')
            ->with('success', 'Publisher updated.');
    }

    public function destroy(Publisher $publisher)
    {
        $publisher->delete();
        return back()->with('success', 'Publisher deleted.');
    }
}