@extends('layouts.master')

@section('title')
    Invoice {{ $invoice->invoice_number }}
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Invoices
@endslot
@slot('title')
    Invoice Details
@endslot
@endcomponent

<div class="row justify-content-center">
    <div class="col-xxl-9">
        <div class="card" id="demo">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-header border-bottom-dashed p-4">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <img src="{{ URL::asset('build/images/logo-dark.png') }}" class="card-logo card-logo-dark" alt="logo dark" height="17">
                                <img src="{{ URL::asset('build/images/logo-light.png') }}" class="card-logo card-logo-light" alt="logo light" height="17">
                                <div class="mt-sm-5 mt-4">
                                    <h6 class="text-muted text-uppercase fw-semibold">Address</h6>
                                    <p class="text-muted mb-1" id="address-details">California, United States</p>
                                    <p class="text-muted mb-0" id="zip-code"><span>Zip-code:</span> 90201</p>
                                </div>
                            </div>
                            <div class="flex-shrink-0 mt-sm-0 mt-3">
                                <h6><span class="text-muted fw-normal">Invoice No:</span><span id="legal-register-no"> {{ $invoice->invoice_number }}</span></h6>
                                <h6><span class="text-muted fw-normal">Date:</span><span id="email"> {{ $invoice->invoice_date->format('M d, Y') }}</span></h6>
                                <h6><span class="text-muted fw-normal">Due Date:</span><span id="contact-no"> {{ $invoice->due_date->format('M d, Y') }}</span></h6>
                                <h6 class="mb-0"><span class="text-muted fw-normal">Status:</span>
                                    <span class="badge 
                                        @if($invoice->status == 'paid') badge-soft-success
                                        @elseif($invoice->status == 'partially_paid') badge-soft-warning
                                        @else badge-soft-danger
                                        @endif
                                    ">{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</span>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-lg-3 col-6">
                                <p class="text-muted mb-2 text-uppercase fw-semibold">Billed To</p>
                                <h5 class="fs-14 mb-0">{{ $invoice->member->name }}</h5>
                                <p class="text-muted mb-0">{{ $invoice->member->email }}</p>
                                <p class="text-muted mb-0">{{ $invoice->member->phone }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                <thead>
                                    <tr class="table-active">
                                        <th scope="col" style="width: 50px;">#</th>
                                        <th scope="col" class="text-start">Description</th>
                                        <th scope="col" class="text-end">Rate</th>
                                        <th scope="col" class="text-end">Quantity</th>
                                        <th scope="col" class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoice->items as $index => $item)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td class="text-start">{{ $item->description }}</td>
                                        <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                                        <td class="text-end">{{ $item->quantity }}</td>
                                        <td class="text-end">${{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="border-top border-top-dashed mt-2">
                            <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:250px">
                                <tbody>
                                    <tr>
                                        <td>Sub Total</td>
                                        <td class="text-end">${{ number_format($invoice->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Estimated Tax</td>
                                        <td class="text-end">${{ number_format($invoice->tax_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Discount</td>
                                        <td class="text-end">-${{ number_format($invoice->discount_amount, 2) }}</td>
                                    </tr>
                                    <tr class="border-top border-top-dashed fs-15">
                                        <th scope="row">Total Amount</th>
                                        <th class="text-end">${{ number_format($invoice->total_amount, 2) }}</th>
                                    </tr>
                                    <tr>
                                        <td>Paid Amount</td>
                                        <td class="text-end text-success">-${{ number_format($invoice->paid_amount, 2) }}</td>
                                    </tr>
                                    <tr class="border-top border-top-dashed fs-15">
                                        <th scope="row">Balance Due</th>
                                        <th class="text-end text-danger">${{ number_format($invoice->remaining_balance, 2) }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <h6 class="text-muted text-uppercase fw-semibold mb-3">Payment Details:</h6>
                            <p class="text-muted mb-1">Payment Method: <span class="fw-medium" id="payment-method">Mastercard</span></p>
                            <p class="text-muted mb-1">Card Holder: <span class="fw-medium" id="card-holder-name">{{ $invoice->member->name }}</span></p>
                            <p class="text-muted mb-1">Card Number: <span class="fw-medium" id="card-number">xxx xxxx xxxx 1234</span></p>
                            <p class="text-muted">Total Amount: <span class="fw-medium" id="">$ {{ number_format($invoice->total_amount, 2) }}</span></p>
                        </div>
                        <div class="mt-4">
                            <div class="alert alert-info">
                                <p class="mb-0"><span class="fw-semibold">NOTES:</span>
                                    <span id="note">{{ $invoice->notes ?? 'No additional notes.' }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                            <a href="javascript:window.print()" class="btn btn-success"><i class="ri-printer-line align-bottom me-1"></i> Print</a>
                            @if($invoice->remaining_balance > 0)
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                                    <i class="ri-money-dollar-circle-line align-bottom me-1"></i> Add Payment
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('invoices.addPayment', $invoice->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Amount ($)</label>
                        <input type="number" name="amount" class="form-control" value="{{ $invoice->remaining_balance }}" max="{{ $invoice->remaining_balance }}" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cheque">Cheque</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference Number</label>
                        <input type="text" name="reference_number" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
