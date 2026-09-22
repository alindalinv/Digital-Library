@extends('layouts.admin.app')

@section('content')
    <div class="mx-auto max-w-2xl rounded-2xl border border-gray-200 bg-white p-5 lg:p-6 dark:border-gray-800 dark:bg-gray-900">
        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white">Edit E-book File</h3>
        <form method="POST" action="{{ route('admin.ebook-files.update', $ebookFile) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')
            @include('admin.pages.ebook-files._form', ['ebookFile' => $ebookFile, 'selectedBookId' => null])
            <div class="flex justify-end gap-3"><a href="{{ route('admin.ebook-files.index') }}" class="rounded-lg border px-4 py-2 text-sm dark:border-gray-700 dark:text-gray-300">Cancel</a><button class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Save changes</button></div>
        </form>
    </div>
@endsection
