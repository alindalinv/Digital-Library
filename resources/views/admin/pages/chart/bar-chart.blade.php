@extends('layouts.app')

@section('content')
    <x-admin.common.page-breadcrumb pageTitle="Bar chart" />
    <div class="space-y-6">
        <x-admin.common.component-card title="Bar chart 1">
            <!-- ====== Bar Chart One Start -->
            <div class="custom-scrollbar max-w-full overflow-x-auto">
                <div id="chartOne" class="min-w-[1000px]"></div>
            </div>
            <!-- ====== Bar Chart One End -->
        </x-admin.common.component-card>

        <x-admin.common.component-card title="Bar chart 2">
            <!-- ====== Bar Chart Two Start -->
            <div class="custom-scrollbar max-w-full overflow-x-auto">
                <div id="chartSix" class="min-w-[1000px]"></div>
            </div>
            <!-- ====== Bar Chart Two End -->
        </x-admin.common.component-card>
    </div>
@endsection
