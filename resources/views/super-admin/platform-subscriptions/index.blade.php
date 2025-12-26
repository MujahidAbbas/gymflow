@extends('layouts.master')

@section('title') Platform Subscription Tiers @endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Platform @endslot
@slot('title') Subscription Tiers @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0">Platform Subscription Tiers</h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('super-admin.platform-subscriptions.create') }}" class="btn btn-primary">
                            <i class="ri-add-line align-bottom me-1"></i> Add Tier
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    {!! $dataTable->scripts() !!}
    @endpush