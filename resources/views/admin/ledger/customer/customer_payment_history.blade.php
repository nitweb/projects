@extends('admin.admin_master')
@section('admin')
    <style>
        .table>:not(caption)>*>* {
            padding: 5px !important;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-12">
                                <div class="company-details mt-5">
                                    <h5>Customer Name : {{ $customerInfo->name }}</h5>
                                    <p class="mb-0">Address : {{ $customerInfo->address }}</p>
                                    <p class="mb-0">Phone : {{ $customerInfo->phone }}</p>
                                    <p class="mb-4">E-mail : {{ $customerInfo->email }}</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="payment-details">
                                    {{-- <table class="table table-bordered border-dark text-center text-dark" width="100%"> --}}
                                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <h6 class="fw-bold">Sl. No</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Invoice</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Amount</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Note</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Date</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Paid By</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($totalOrder as $key => $details)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>

                                                    <td>
                                                        <a
                                                            href="{{ route('invoice.view', $details->invoice_id) }}">{{ $details->invoice->invoice_no }}</a>
                                                    </td>
                                                    <td>BDT {{ number_format($details->current_paid_amount) }}</td>
                                                    <td>{{ $details->note }}</td>
                                                    <td> {{ date('d-m-Y', strtotime($details->date)) }}</td>
                                                    @php
                                                        $bank_name = App\Models\Bank::findOrFail(
                                                            $details->bank_name,
                                                        )->first()['name'];
                                                    @endphp
                                                    <td><span class="badge bg-info">{{ $bank_name }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
