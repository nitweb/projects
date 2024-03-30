@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header py-3 bg-white">
                        <div class="row">
                            <div class="col-12 pt-3 pb-0 d-flex justify-content-center align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">Customer Report</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="datatable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th width="5%">Sl</th>
                                        <th>Customer Name</th>
                                        <th>Grand Total</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Customer Name</th>
                                        <th>Grand Total</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                    </tr>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($allCustomer as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $item->name }}
                                            </td>
                                            <td>
                                                @php
                                                    $total_amount = App\Models\Payment::where('customer_id', $item->id)->sum('total_amount');
                                                @endphp
                                                {{ $total_amount }}
                                            </td>
                                            <td>
                                                @php
                                                    $paid_amount = App\Models\Payment::where('customer_id', $item->id)->sum('paid_amount');
                                                @endphp
                                                {{ $paid_amount }}
                                            </td>
                                            <td>
                                                @php
                                                    $due_amount = App\Models\Payment::where('customer_id', $item->id)->sum('due_amount');
                                                @endphp
                                                {{ $due_amount }}
                                            </td>
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

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('backend/assets/js/code.js') }}"></script>
@endsection
