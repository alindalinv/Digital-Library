@extends('layouts.admin.app')

@section('title', 'Preview — ' . basename($ebookFile->file_path))

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">

        {{-- =========================================================
        Header card — book + actions
        ========================================================== --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 lg:p-6 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div class="min-w-0">
                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                        <a href="{{ route('admin.ebook-files.index') }}"
                            class="hover:text-gray-700 dark:hover:text-gray-300">
                            E-book Files
                        </a>
                        <span>/</span>
                        <span>Preview</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white truncate">
                        {{ $ebookFile->book->title ?? 'Unknown book' }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 flex items-center gap-2 flex-wrap">
                        <span
                            class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                            {{ strtoupper($ebookFile->file_type) }}
                        </span>
                        @if ($ebookFile->is_primary)
                            <span
                                class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                Primary
                            </span>
                        @endif
                        @if ($ebookFile->file_size)
                            <span>{{ number_format($ebookFile->file_size / 1048576, 2) }} MB</span>
                        @endif
                    </p>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    {{-- Preview --}}
                    <button type="button" data-pdf-open data-pdf-url="{{ route('admin.ebook-files.embed', $ebookFile) }}"
                        data-pdf-title="{{ basename($ebookFile->file_path) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            viewBox="0 0 16 16">
                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                            <path
                                d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                        </svg>
                        Preview
                    </button>

                    {{-- Download --}}
                    @can('ebook-files.download')
                        <a href="{{ route('admin.ebook-files.download', $ebookFile) }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M.5 9.9a.5.5 0 0 1 .5.5v2.1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.1a.5.5 0 0 1 1 0v2.1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.1a.5.5 0 0 1 .5-.5" />
                                <path
                                    d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
                            </svg>
                            Download
                        </a>
                    @endcan

                    {{-- Edit --}}
                    @can('ebook-files.update')
                        <a href="{{ route('admin.ebook-files.edit', $ebookFile) }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325" />
                            </svg>
                            Edit
                        </a>
                    @endcan

                    {{-- Replace file --}}
                    @can('ebook-files.update')
                        <button type="button" data-replace-open
                            class="inline-flex items-center gap-2 rounded-lg border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-medium text-amber-700 hover:bg-amber-100 dark:border-amber-700 dark:bg-amber-900/30 dark:text-amber-300 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41m-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9" />
                                <path fill-rule="evenodd"
                                    d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5 5 0 0 0 8 3M3.1 9a5 5 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9z" />
                            </svg>
                            Replace
                        </button>
                    @endcan

                    {{-- Delete --}}
                    @can('ebook-files.delete')
                        <form method="POST" action="{{ route('admin.ebook-files.destroy', $ebookFile) }}"
                            onsubmit="return confirm('Delete this file permanently? This cannot be undone.');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-lg border border-red-300 bg-red-50 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-100 dark:border-red-700 dark:bg-red-900/30 dark:text-red-300 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                    <path
                                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                </svg>
                                Delete
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>

        {{-- =========================================================
        Metadata card
        ========================================================== --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 lg:p-6 dark:border-gray-800 dark:bg-gray-900">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">File details</h4>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                <div class="flex justify-between sm:flex-col sm:justify-start">
                    <dt class="text-gray-500">Filename</dt>
                    <dd class="font-medium text-gray-800 dark:text-white truncate"
                        title="{{ basename($ebookFile->file_path) }}">
                        {{ basename($ebookFile->file_path) }}
                    </dd>
                </div>
                <div class="flex justify-between sm:flex-col sm:justify-start">
                    <dt class="text-gray-500">Type</dt>
                    <dd class="font-medium text-gray-800 dark:text-white">{{ strtoupper($ebookFile->file_type) }}</dd>
                </div>
                <div class="flex justify-between sm:flex-col sm:justify-start">
                    <dt class="text-gray-500">Size</dt>
                    <dd class="font-medium text-gray-800 dark:text-white">
                        {{ $ebookFile->file_size ? number_format($ebookFile->file_size / 1048576, 2) . ' MB' : '—' }}
                    </dd>
                </div>
                <div class="flex justify-between sm:flex-col sm:justify-start">
                    <dt class="text-gray-500">Primary</dt>
                    <dd class="font-medium text-gray-800 dark:text-white">
                        {{ $ebookFile->is_primary ? 'Yes' : 'No' }}
                    </dd>
                </div>
                <div class="flex justify-between sm:flex-col sm:justify-start">
                    <dt class="text-gray-500">Uploaded</dt>
                    <dd class="font-medium text-gray-800 dark:text-white">
                        {{ $ebookFile->created_at?->format('M j, Y H:i') }}
                    </dd>
                </div>
                <div class="flex justify-between sm:flex-col sm:justify-start">
                    <dt class="text-gray-500">Updated</dt>
                    <dd class="font-medium text-gray-800 dark:text-white">
                        {{ $ebookFile->updated_at?->format('M j, Y H:i') }}
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Back link --}}
        <div>
            <a href="{{ route('admin.ebook-files.index') }}" class="text-sm text-brand-500 hover:text-brand-600">← Back to
                files</a>
        </div>
    </div>

    {{-- =========================================================
    PDF preview overlay
    ========================================================== --}}
    {{-- =========================================================
    PDF Preview Modal (TailAdmin style)
    ========================================================== --}}
    <div id="pdfPreviewModal"
        class="fixed inset-0 z-[99999] hidden items-center justify-center overflow-y-auto bg-gray-900/60 p-4 backdrop-blur-sm"
        role="dialog" aria-modal="true" aria-labelledby="pdfPreviewTitle">

        <div class="relative w-full max-w-6xl rounded-2xl bg-white shadow-2xl dark:bg-gray-900">
            {{-- Header --}}
            <div class="flex items-center justify-between gap-4 border-b border-gray-200 px-5 py-4 dark:border-gray-800">
                <div class="flex min-w-0 items-center gap-3">
                    <div
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-500 dark:bg-brand-500/15 dark:text-brand-400">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path
                                d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2z" />
                        </svg>
                    </div>
                    <h3 id="pdfPreviewTitle" class="truncate text-base font-semibold text-gray-800 dark:text-white/90">
                        Preview
                    </h3>
                </div>

                <div class="flex items-center gap-2">
                    {{-- Download --}}
                    <a id="pdfPreviewDownload" href="#" style="display:none;"
                        class="hidden sm:inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path
                                d="M.5 9.9a.5.5 0 0 1 .5.5v2.1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.1a.5.5 0 0 1 1 0v2.1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.1a.5.5 0 0 1 .5-.5" />
                            <path
                                d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
                        </svg>
                        Download
                    </a>

                    {{-- Open in new tab --}}
                    <a id="pdfPreviewOpenTab" href="#" target="_blank" rel="noopener"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5" />
                            <path fill-rule="evenodd"
                                d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0z" />
                        </svg>
                        <span class="hidden sm:inline">Open tab</span>
                    </a>

                    {{-- Close --}}
                    <button type="button" id="pdfPreviewClose"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
                        aria-label="Close preview">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path
                                d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Body / iframe --}}
            <div class="relative h-[80vh] w-full overflow-hidden rounded-b-2xl bg-gray-800 dark:bg-black">
                {{-- Loading spinner --}}
                <div id="pdfPreviewLoading"
                    class="absolute inset-0 z-10 flex flex-col items-center justify-center gap-3 bg-gray-800 text-gray-300">
                    <div class="h-9 w-9 animate-spin rounded-full border-[3px] border-white/25 border-t-white"></div>
                    <p class="text-sm">Loading PDF…</p>
                </div>

                <iframe id="pdfPreviewFrame" src="about:blank" class="h-full w-full border-0" title="PDF preview"></iframe>
            </div>
        </div>
    </div>

    {{-- =========================================================
    Replace-file overlay
    ========================================================== --}}
    @can('ebook-files.update')
        <div id="replaceModal" style="display:none; position:fixed; inset:0; z-index:1055; background:rgba(0,0,0,.6);"
            aria-hidden="true" role="dialog" aria-modal="true">
            <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; padding:16px;">
                <div style="background:#fff; border-radius:12px; width:100%; max-width:480px; overflow:hidden; box-shadow:0 20px 50px rgba(0,0,0,.3);"
                    class="dark:bg-gray-900">
                    <form method="POST" action="{{ route('admin.ebook-files.update', $ebookFile) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div style="padding:16px 20px; border-bottom:1px solid #e5e7eb;" class="dark:border-gray-800">
                            <h3 style="font-size:15px; font-weight:600; margin:0;" class="text-gray-800 dark:text-white">
                                Replace file
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">The old file will be deleted after upload.</p>
                        </div>

                        <div style="padding:20px;">
                            <input type="hidden" name="book_id" value="{{ $ebookFile->book_id }}">
                            <input type="hidden" name="is_primary" value="{{ $ebookFile->is_primary ? 1 : 0 }}">

                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                New file (PDF, EPUB, MOBI — max 50 MB)
                            </label>
                            <input type="file" name="file" accept=".pdf,.epub,.mobi,application/pdf" required
                                class="block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-brand-500 file:text-white hover:file:bg-brand-600 file:cursor-pointer">

                            @error('file')
                                <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="padding:12px 20px; border-top:1px solid #e5e7eb; display:flex; justify-content:flex-end; gap:8px;"
                            class="dark:border-gray-800">
                            <button type="button" data-replace-close
                                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800">
                                Cancel
                            </button>
                            <button type="submit"
                                class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                                Upload & replace
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    {{-- =========================================================
    Modal JS
    ========================================================== --}}
    <script>
        (function () {
            const modal = document.getElementById('pdfPreviewModal');
            const frame = document.getElementById('pdfPreviewFrame');
            const titleEl = document.getElementById('pdfPreviewTitle');
            const closeBtn = document.getElementById('pdfPreviewClose');
            const loading = document.getElementById('pdfPreviewLoading');
            const dLoad = document.getElementById('pdfPreviewDownload');
            const dTab = document.getElementById('pdfPreviewOpenTab');

            let lastFocused = null;

            function openModal(url, title, downloadUrl) {
                lastFocused = document.activeElement;

                titleEl.textContent = title || 'Preview';
                loading.style.display = 'flex';
                frame.src = url;

                if (downloadUrl) {
                    dLoad.href = downloadUrl;
                    dLoad.style.display = '';
                    dLoad.classList.remove('hidden');
                } else {
                    dLoad.style.display = 'none';
                }
                dTab.href = url;

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';

                setTimeout(() => closeBtn.focus(), 30);
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                frame.src = 'about:blank';
                document.body.style.overflow = '';
                if (lastFocused?.focus) lastFocused.focus();
            }

            // Hide spinner once the iframe has loaded
            frame.addEventListener('load', function () {
                if (frame.src && frame.src !== 'about:blank') {
                    setTimeout(() => { loading.style.display = 'none'; }, 150);
                }
            });

            // Open from any [data-pdf-open] trigger
            document.addEventListener('click', function (e) {
                const trigger = e.target.closest('[data-pdf-open]');
                if (trigger) {
                    e.preventDefault();
                    openModal(
                        trigger.dataset.pdfUrl,
                        trigger.dataset.pdfTitle,
                        trigger.dataset.pdfDownload || null
                    );
                    return;
                }

                if (e.target === closeBtn || e.target.closest('#pdfPreviewClose')) {
                    e.preventDefault();
                    closeModal();
                    return;
                }

                // Backdrop click
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Esc closes
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });

            // Close from inside the iframe
            window.addEventListener('message', function (e) {
                if (e.data === 'pdf-modal-close') closeModal();
            });
        })();
    </script>
@endsection