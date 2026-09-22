<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:categories.view')->only(['index', 'show']);
        $this->middleware('permission:categories.create')->only(['create', 'store']);
        $this->middleware('permission:categories.update')->only(['edit', 'update']);
        $this->middleware('permission:categories.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->toString();
        $categories = Category::with(['parent:id,name'])->withCount(['books', 'children'])
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('order')->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.categories.index', compact('categories', 'search'));
    }

    public function create()
    {
        return view('admin.categories.create', ['parents' => $this->parentOptions()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $data['slug'] = Str::slug($data['name']);
        $data['status'] = $request->boolean('status');
        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', [
            'category' => $category,
            'parents' => $this->parentOptions($category),
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate($this->rules($category));

        $data['slug'] = Str::slug($data['name']);
        $data['status'] = $request->boolean('status');
        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->books()->exists()) {
            return back()->with('error', 'This category cannot be deleted while books are assigned to it.');
        }

        $category->children()->update(['parent_id' => null]);
        $category->delete();
        return back()->with('success', 'Category deleted successfully.');
    }

    public function show(Category $category)
    {
        return redirect()->route('admin.categories.edit', $category);
    }

    private function rules(?Category $category = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category?->id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'parent_id' => ['nullable', 'exists:categories,id', Rule::notIn([$category?->id])],
            'order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'status' => ['nullable', 'boolean'],
        ];
    }

    private function parentOptions(?Category $category = null)
    {
        return Category::query()
            ->when($category, fn ($query) => $query->whereKeyNot($category->id))
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}
