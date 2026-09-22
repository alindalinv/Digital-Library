@extends('layouts.admin.app')

@section('content')
    <x-admin.common.page-breadcrumb pageTitle="E-book File" />
    <div class="mx-auto max-w-2xl rounded-2xl border border-gray-200 bg-white p-5 lg:p-6 dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-start justify-between gap-4"><div><h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $ebookFile->book->title }}</h3><p class="mt-1 text-sm text-gray-500">{{ strtoupper($ebookFile->file_type) }} file @if($ebookFile->is_primary) · Primary @endif</p></div><a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($ebookFile->file_path) }}" target="_blank" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Open file</a></div>
        <dl class="mt-6 divide-y divide-gray-100 text-sm dark:divide-gray-800"><div class="flex justify-between py-3"><dt class="text-gray-500">Size</dt><dd class="dark:text-white">{{ $ebookFile->file_size ? number_format($ebookFile->file_size / 1048576, 2) . ' MB' : '—' }}</dd></div><div class="flex justify-between py-3"><dt class="text-gray-500">Uploaded</dt><dd class="dark:text-white">{{ $ebookFile->created_at->format('M j, Y H:i') }}</dd></div></dl>
        <div class="mt-6"><a href="{{ route('admin.ebook-files.index') }}" class="text-sm text-brand-500 hover:text-brand-600">← Back to files</a></div>
    </div>
@endsection
