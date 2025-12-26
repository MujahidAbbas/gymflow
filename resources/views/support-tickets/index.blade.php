@extends('layouts.master')

@section('title') Support Tickets @endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Support @endslot
@slot('title') Support Tickets @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Support Tickets</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('support-tickets.create') }}" class="btn btn-success btn-sm">
                        <i class="ri-add-line me-1"></i> Create Ticket
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="ri-check-line me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    {!! $dataTable->table() !!}
                </div>


            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    {!! $dataTable->scripts() !!}
@endsection