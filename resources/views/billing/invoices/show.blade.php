@extends('layouts.main')
@section('title', 'Invoices')
@section('page-title', 'Invoices')
@section('breadcrumb')
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-9">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-header border-bottom-dashed p-4">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <img src="{{ asset(settings('logo')) }}" class="card-logo user-profile-image img-fluid" alt="logo" height="17">
                                    <div class="mt-sm-5 mt-4">
                                        <h6 class="text-muted text-uppercase fw-semibold">Address</h6>
                                        <p class="text-muted mb-1" id="address-details">{{ settings('company_address') }}</p>
                                        <p class="text-muted mb-0" id="zip-code"><span>Zip-code:</span> {{ settings('company_zip') ?? '---' }}</p>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 mt-sm-0 mt-3">
                                    <h6><span class="text-muted fw-normal">Legal Registration No:</span><span id="legal-register-no">{{ settings('company_registration_no') ?? '---' }}</span></h6>
                                    <h6><span class="text-muted fw-normal">Email:</span><span id="email">{{ settings('company_email') }}</span></h6>
                                    <h6><span class="text-muted fw-normal">Website:</span> <a href="{{ url('/') }}" class="link-primary" target="_blank" id="website">{{ url('/') }}</a></h6>
                                    <h6 class="mb-0"><span class="text-muted fw-normal">Contact No: </span><span id="contact-no">{{ settings('company_phone_number') }}</span></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Invoice No</p>
                                    <h5 class="fs-14 mb-0">#<span id="invoice-no">{{ $item->invoice_number }}</span></h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Date</p>
                                    <h5 class="fs-14 mb-0">
                                        <span id="invoice-date">{{ $item->invoice_date }}</span>
                                        <small class="text-muted" id="invoice-time">{{ \Carbon\Carbon::parse($item->invoice_date)->format('h:iA') }}</small>
                                    </h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Payment Status</p>
                                    <span class="badge bg-success-subtle text-success fs-11" id="payment-status">{{ ucfirst($item->status) }}</span>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Invoice Due Date</p>
                                    <h5 class="fs-14 mb-0"> <span id="total-amount">{{ ($item->due_date) }}</span></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card-body p-4 border-top border-top-dashed">
                            <div class="row g-3">
                                <div class="col-6">
                                    <h6 class="text-muted text-uppercase fw-semibold mb-3">Billing Address</h6>
                                    <p class="fw-medium mb-2" id="billing-name">{{ $item->client->fullname ?? '---' }}</p>
                                    <p class="text-muted mb-1" id="billing-address-line-1">{{ $item->client->address ?? '---' }}</p>
                                    <p class="text-muted mb-1"><span>Phone: </span><span id="billing-phone-no">{{ $item->client->phone_number ?? '---' }}</span></p>
                                    <p class="text-muted mb-0"><span>Email: </span><span id="billing-tax-no">{{ $item->client->email ?? '---' }}</span></p>
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
                                        <th>#</th>
                                        <th class="text-start">Product Details</th>
                                        <th>Rate</th>
                                        <th>Quantity</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $counter = 1; @endphp
                                    <tr>
                                        <th scope="row">{{ str_pad($counter++, 2, '0', STR_PAD_LEFT) }}</th>
                                        <td class="text-start">
                                            <span class="fw-medium">{{ $item->booking->property->property_name }} Booking Fee</span>
                                            <p class="text-muted mb-0">Booking Ref: {{ $item->booking->booking_number }}</p>
                                        </td>
                                        <td> {{ format_money($item->booking->total_price) }}</td>
                                        <td>1</td>
                                        <td class="text-end"> {{ format_money($item->booking->total_price) }}</td>
                                    </tr>
                                    @foreach ($item->items as $inv)
                                        <tr>
                                            <th scope="row">{{ str_pad($counter++, 2, '0', STR_PAD_LEFT) }}</th>
                                            <td class="text-start">
                                                <span class="fw-medium">{{ $inv->lookup->name ?? 'Custom Item' }}</span>
                                                <p class="text-muted mb-0">{{ $inv->description }}</p>
                                            </td>
                                            <td> {{ format_money($inv->amount / $inv->quantity) }}</td>
                                            <td>{{ $inv->quantity }}</td>
                                            <td class="text-end"> {{ format_money($inv->amount) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="border-top border-top-dashed mt-2">
                                <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:250px">
                                    <tbody>
                                    <tr>
                                        <td>Booking Total</td>
                                        <td class="text-end"> {{ format_money($item->sub_total_amount) }}</td>
                                    </tr>
                                    <tr class="border-top border-top-dashed fs-15">
                                        <th>Total Amount</th>
                                        <th class="text-end"> {{ format_money($item->total_amount) }}</th>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <h6 class="text-muted text-uppercase fw-semibold mb-3">Payment Details:</h6>
                                <p class="text-muted mb-1">Total Paid: <span class="fw-medium">GHS {{ number_format($item->total_paid, 2) }}</span></p>
                            </div>
                            <div class="mt-4">
                                <div class="alert alert-info">
                                    <p class="mb-0">
                                        <span class="fw-semibold">NOTES:</span>
                                        <span id="note">All accounts are to be paid within 7 days from receipt of invoice. To be paid by cheque, credit card, or direct payment online.</span>
                                    </p>
                                </div>
                            </div>
                            <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                <a href="{{route("payments.create", ["invoice_id" => $item->id])}}" class="btn btn-primary"><i class="ri-wallet-2-line align-bottom me-1"></i> Make Payment</a>
                                <a href="javascript:window.print()" class="btn btn-success"><i class="ri-printer-line align-bottom me-1"></i> Print</a>
                                <a href="javascript:void(0);" class="btn btn-primary"><i class="ri-download-2-line align-bottom me-1"></i> Download</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
@endsection
