<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:categories.view')->only(['index', 'show']);
        $this->middleware('permission:categories.create')->only(['create', 'store']);
        $this->middleware('permission:categories.update')->only(['edit', 'update']);
        $this->middleware('permission:categories.delete')->only('destroy');
    }

    public function index(Request $request): View|JsonResponse
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status', 'all')->toString();

        if (! in_array($status, ['all', 'active', 'inactive'], true)) {
            $status = 'all';
        }

        $categories = Category::with(['parent:id,name'])->withCount(['books', 'children'])
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->when($status !== 'all', fn ($query) => $query->where('status', $status === 'active'))
            ->orderBy('order')->orderBy('name')->paginate(15)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('admin.categories._results', compact('categories'))->render(),
                'total' => $categories->total(),
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'next_page_url' => $categories->nextPageUrl(),
                'prev_page_url' => $categories->previousPageUrl(),
            ]);
        }

        return view('admin.categories.index', compact('categories', 'search', 'status') + ['title' => 'Categories']);
    }

    public function create()
    {
        return view('admin.categories.create', [
            'parents' => $this->parentOptions(),
            'title' => 'Create Category',
        ]);
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
            'title' => 'Edit Category',
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
