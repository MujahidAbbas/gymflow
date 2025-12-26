@extends('layouts.master')

@section('title')
    Health Measurement Details
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Health
@endslot
@slot('title')
    Measurement Details
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Measurement Information</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('healths.edit', $health->id) }}" class="btn btn-primary btn-sm">
                        <i class="ri-pencil-line align-middle me-1"></i> Edit Measurement
                    </a>
                    <a href="{{ route('healths.index') }}" class="btn btn-secondary btn-sm ms-1">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0" scope="row">Member :</th>
                                        <td class="text-muted">{{ $health->member->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Date :</th>
                                        <td class="text-muted">{{ $health->measurement_date ? $health->measurement_date->format('d M, Y') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">BMI :</th>
                                        <td class="text-muted">
                                            @if($health->bmi)
                                                <span class="badge 
                                                    @if($health->bmi_category == 'Normal') bg-success
                                                    @elseif($health->bmi_category == 'Overweight') bg-warning text-dark
                                                    @elseif($health->bmi_category == 'Obese') bg-danger
                                                    @else bg-info
                                                    @endif
                                                ">{{ $health->bmi }} - {{ $health->bmi_category }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0" scope="row">Notes :</th>
                                        <td class="text-muted">{{ $health->notes ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12">
                        <h5 class="mb-3">Measurements</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Measurement Type</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($health->measurements as $key => $value)
                                        <tr>
                                            <td>{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                            <td>{{ $value }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">No measurements recorded</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
