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
        $this->middleware('permission:ebook-files.view')->only(['index', 'show', 'download', 'view', 'stream', 'embed']);
        $this->middleware('permission:ebook-files.create')->only(['create', 'store']);
        $this->middleware('permission:ebook-files.update')->only(['edit', 'update']);
        $this->middleware('permission:ebook-files.delete')->only('destroy');
    }

    public function index(Request $request): View|JsonResponse
    {
        $search = $request->string('search')->trim()->toString();
        $ebookFiles = EbookFile::with('book:id,title,isbn')
            ->when($search, fn($query) => $query->whereHas('book', fn($book) => $book
                ->where('title', 'like', "%{$search}%")
                ->orWhere('isbn', 'like', "%{$search}%")))
            ->latest()->paginate(15)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('admin.ebook-files._results', compact('ebookFiles'))->render(),
                'total' => $ebookFiles->total(),
            ]);
        }

        return view('admin.ebook-files.index', compact('ebookFiles', 'search') + [
            'title' => 'E-book Files',
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.ebook-files.create', [
            'books' => Book::orderBy('title')->get(['id', 'title', 'isbn']),
            'selectedBookId' => $request->integer('book_id') ?: null,
            'title' => 'Upload E-book File',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $upload = $validated['file'];

        $ebookFile = DB::transaction(function () use ($validated, $upload) {

            $isPrimary = $validated['is_primary'] ?? false;

            /*
            * If this is the first file for the book,
            * it must be the primary file.
            */
            if (!EbookFile::where('book_id', $validated['book_id'])->exists()) {
                $isPrimary = true;
            }

            /*
            * Only one file per book can be primary.
            */
            if ($isPrimary) {
                EbookFile::where('book_id', $validated['book_id'])
                    ->update([
                        'is_primary' => false,
                    ]);
            }

            /*
            * Store the physical file first.
            */
            $path = $upload->store('ebooks', 'local');

            /*
            * Create database record.
            */
            return EbookFile::create([
                'book_id' => $validated['book_id'],
                'file_path' => $path,
                'file_type' => strtolower(
                    $upload->getClientOriginalExtension()
                ),
                'file_size' => $upload->getSize(),
                'is_primary' => $isPrimary,
            ]);
        });

        return redirect()
            ->route('admin.ebook-files.show', $ebookFile)
            ->with('success', 'E-book file uploaded successfully.');
    }
    public function show(EbookFile $ebookFile): View
    {
        $ebookFile->load('book:id,title,isbn');
        return view('admin.ebook-files.show', compact('ebookFile') + [
            'title' => 'E-book File',
        ]);
    }

    public function edit(EbookFile $ebookFile): View
    {
        return view('admin.ebook-files.edit', [
            'ebookFile' => $ebookFile,
            'books' => Book::orderBy('title')->get(['id', 'title', 'isbn']),
            'title' => 'Edit E-book File',
        ]);
    }

    public function update(Request $request, EbookFile $ebookFile): RedirectResponse
    {
        $validated = $request->validate($this->rules(false));
        $oldPath = $ebookFile->file_path;
        $newPath = null;

        DB::transaction(function () use ($request, $validated, $ebookFile, &$newPath) {
            $isPrimary = $validated['is_primary'] ?? false;
            if (
                !$isPrimary && !EbookFile::where('book_id', $validated['book_id'])
                    ->whereKeyNot($ebookFile->id)->exists()
            ) {
                $isPrimary = true;
            }

            if ($isPrimary) {
                EbookFile::where('book_id', $validated['book_id'])
                    ->whereKeyNot($ebookFile->id)->update(['is_primary' => false]);
            }

            $data = ['book_id' => $validated['book_id'], 'is_primary' => $isPrimary];
            if ($request->hasFile('file')) {
                $upload = $validated['file'];
                $newPath = $upload->store('ebooks', 'local');   // ✅ local (was 'public')
                $data += [
                    'file_path' => $newPath,
                    'file_type' => strtolower($upload->getClientOriginalExtension()),
                    'file_size' => $upload->getSize(),
                ];
            }
            $ebookFile->update($data);
        });

        if ($newPath) {
            Storage::disk('local')->delete($oldPath);
        }

        return redirect()->route('admin.ebook-files.show', $ebookFile)->with('success', 'E-book file updated successfully.');
    }

    public function destroy(EbookFile $ebookFile): RedirectResponse
    {
        $path = $ebookFile->file_path;
        $ebookFile->delete();
        Storage::disk('local')->delete($path);

        return redirect()->route('admin.ebook-files.index')->with('success', 'E-book file deleted successfully.');
    }

    public function download(EbookFile $ebookFile)
    {
        $disk = Storage::disk('local');

        abort_unless($disk->exists($ebookFile->file_path), 404);

        return $disk->download(
            $ebookFile->file_path,
            basename($ebookFile->file_path)
        );
    }

    private function rules(bool $requiresFile = true): array
    {
        return [
            'book_id' => ['required', 'exists:books,id'],
            'file' => [$requiresFile ? 'required' : 'nullable', 'file', 'mimes:pdf,epub,mobi', 'max:51200'],
            'is_primary' => ['nullable', 'boolean'],
        ];
    }
    public function view(Request $request, EbookFile $ebookFile): View
    {
        $disk = Storage::disk('local');
        abort_unless($disk->exists($ebookFile->file_path), 404);

        $isAdmin = str_starts_with((string) request()->route()->getName(), 'admin.');

        // If opened inside an iframe modal → return bare viewer (no layout)
        if ($request->boolean('modal')) {
            return view('partials.pdfjs-modal', [
                'ebookFile' => $ebookFile,
                'isAdmin' => $isAdmin,
            ]);
        }

        return view(
            $isAdmin ? 'admin.ebooks.show' : 'frontend.ebooks.show',
            [
                'ebookFile' => $ebookFile,
                'isAdmin' => $isAdmin,
            ]
        );
    }
    public function embed(EbookFile $ebookFile): View
    {
        $disk = Storage::disk('local');
        abort_unless($disk->exists($ebookFile->file_path), 404);

        return view('partials.pdfjs-embed', compact('ebookFile'));
    }

    public function stream(EbookFile $ebookFile)
    {
        $disk = Storage::disk('local');
        abort_unless($disk->exists($ebookFile->file_path), 404);

        return response()->file($disk->path($ebookFile->file_path), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($ebookFile->file_path) . '"',
        ]);
    }
}