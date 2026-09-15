@extends('admin.layouts.master')
@section('content')
<!-- Greeting -->
<div class="page-heading">
    <h1>Welcome back, {{ Auth::guard('admin')->user()->name }} 👋</h1>
    <p>Here's what's happening today.</p>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-custom">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box bg-orange-100 text-orange-600">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-0">Total Customers</p>
                    <p class="text-2xl fw-bold mb-0">{{ number_format($total_customers) }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-custom">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box bg-green-100 text-green-600">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-0">Total Subscribers</p>
                    <p class="text-2xl fw-bold mb-0">{{ number_format($total_subscribers) }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-custom">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box bg-purple-100 text-purple-600">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-0">Donations</p>
                    <p class="text-2xl fw-bold mb-0">{{ number_format($total_donations) }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-custom">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box bg-blue-100 text-primary-600">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-0">Total Raised</p>
                    <p class="text-2xl fw-bold mb-0">{{ number_format($total_raised, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
