<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\EbookFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EbookFileController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ebook-files.view')->only(['index', 'show']);
        $this->middleware('permission:ebook-files.create')->only(['create', 'store']);
        $this->middleware('permission:ebook-files.update')->only(['edit', 'update']);
        $this->middleware('permission:ebook-files.delete')->only('destroy');
    }

    public function index(Request $request): View|JsonResponse
    {
        $search = $request->string('search')->trim()->toString();
        $ebookFiles = EbookFile::with('book:id,title,isbn')
            ->when($search, fn ($query) => $query->whereHas('book', fn ($book) => $book
                ->where('title', 'like', "%{$search}%")
                ->orWhere('isbn', 'like', "%{$search}%")))
            ->latest()->paginate(15)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('admin.pages.ebook-files._results', compact('ebookFiles'))->render(),
                'total' => $ebookFiles->total(),
            ]);
        }

        return view('admin.pages.ebook-files.index', compact('ebookFiles', 'search'));
    }

    public function create(Request $request): View
    {
        return view('admin.pages.ebook-files.create', [
            'books' => Book::orderBy('title')->get(['id', 'title', 'isbn']),
            'selectedBookId' => $request->integer('book_id') ?: null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $upload = $validated['file'];

        DB::transaction(function () use ($validated, $upload) {
            $isPrimary = $validated['is_primary'] ?? false;
            if (! EbookFile::where('book_id', $validated['book_id'])->exists()) {
                $isPrimary = true;
            }
            if ($isPrimary) {
                EbookFile::where('book_id', $validated['book_id'])->update(['is_primary' => false]);
            }

            EbookFile::create([
                'book_id' => $validated['book_id'],
                'file_path' => $upload->store('ebooks', 'public'),
                'file_type' => strtolower($upload->getClientOriginalExtension()),
                'file_size' => $upload->getSize(),
                'is_primary' => $isPrimary,
            ]);
        });

        return redirect()->route('admin.ebook-files.index')->with('success', 'E-book file uploaded successfully.');
    }

    public function show(EbookFile $ebookFile): View
    {
        $ebookFile->load('book:id,title,isbn');
        return view('admin.pages.ebook-files.show', compact('ebookFile'));
    }

    public function edit(EbookFile $ebookFile): View
    {
        return view('admin.pages.ebook-files.edit', [
            'ebookFile' => $ebookFile,
            'books' => Book::orderBy('title')->get(['id', 'title', 'isbn']),
        ]);
    }

    public function update(Request $request, EbookFile $ebookFile): RedirectResponse
    {
        $validated = $request->validate($this->rules(false));
        $oldPath = $ebookFile->file_path;
        $newPath = null;

        DB::transaction(function () use ($request, $validated, $ebookFile, &$newPath) {
            $isPrimary = $validated['is_primary'] ?? false;
            if (! $isPrimary && ! EbookFile::where('book_id', $validated['book_id'])
                ->whereKeyNot($ebookFile->id)->exists()) {
                $isPrimary = true;
            }

            if ($isPrimary) {
                EbookFile::where('book_id', $validated['book_id'])
                    ->whereKeyNot($ebookFile->id)->update(['is_primary' => false]);
            }

            $data = ['book_id' => $validated['book_id'], 'is_primary' => $isPrimary];
            if ($request->hasFile('file')) {
                $upload = $validated['file'];
                $newPath = $upload->store('ebooks', 'public');
                $data += [
                    'file_path' => $newPath,
                    'file_type' => strtolower($upload->getClientOriginalExtension()),
                    'file_size' => $upload->getSize(),
                ];
            }
            $ebookFile->update($data);
        });

        if ($newPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return redirect()->route('admin.ebook-files.index')->with('success', 'E-book file updated successfully.');
    }

    public function destroy(EbookFile $ebookFile): RedirectResponse
    {
        $path = $ebookFile->file_path;
        $ebookFile->delete();
        Storage::disk('public')->delete($path);

        return redirect()->route('admin.ebook-files.index')->with('success', 'E-book file deleted successfully.');
    }

    private function rules(bool $requiresFile = true): array
    {
        return [
            'book_id' => ['required', 'exists:books,id'],
            'file' => [$requiresFile ? 'required' : 'nullable', 'file', 'mimes:pdf,epub,mobi', 'max:51200'],
            'is_primary' => ['nullable', 'boolean'],
        ];
    }
}
