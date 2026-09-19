@extends('layouts.admin.app')

@section('content')

<div class="grid grid-cols-12 gap-4 md:gap-6">

    <div class="col-span-12 space-y-6 xl:col-span-7">
        <x-admin.dashboard.metrics />
        <x-admin.dashboard.monthly-sale />
    </div>

    <div class="col-span-12 xl:col-span-5">
        <x-admin.dashboard.monthly-target />
    </div>

    <div class="col-span-12">
        <x-admin.dashboard.statistics-chart />
    </div>

    <div class="col-span-12 xl:col-span-5">
        <x-admin.dashboard.customer-demographic />
    </div>

    <div class="col-span-12 xl:col-span-7">
        <x-admin.dashboard.recent-orders />
    </div>

</div>

@endsection