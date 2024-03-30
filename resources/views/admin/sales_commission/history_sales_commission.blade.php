@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All {{ $title }}</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="{{ route('add.commission') }}">
                    <button class="btn btn-info"> <i class="fa fa-plus-circle" aria-hidden="true"> Add Commission</i>
                    </button>
                </a>
            </h6>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="datatable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Employee</th>
                                        <th>Category</th>
                                        <th>Invoice</th>
                                        <th>Sales Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Employee</th>
                                        <th>Category</th>
                                        <th>Invoice</th>
                                        <th>Sales Amount</th>
                                        <th>Date</th>
                                    </tr>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($comissionHistory as $key => $history)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $history->employee->name }}
                                            </td>
                                            <td>
                                                {{ $history->category->name }}
                                            </td>
                                            <td>
                                                <a target="_blank"
                                                    href="{{ route('invoice.print', $history->invoice_id) }}">
                                                    {{ $history->invoice->invoice_no }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ number_format($history->amount) }}
                                            </td>
                                            <td>
                                                {{ date('d-M,Y', strtotime($history->created_at)) }}
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
    <script src="{{ asset('backend/assets/js/code.js') }}"></script>
@endsection
