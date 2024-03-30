@extends('admin.admin_master')
@section('admin')
    <!-- Begin Page Content -->
    <div class="page-content">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 text-muted ">Filtering Depreciation Result</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active">
                                <div class="d-print-none">
                                    <div class="float-end">
                                        <a href="javascript:window.print()"
                                            class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i>
                                            Print
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <!-- DataTales Example -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive" id="printContent">
                    <h4 class="text-muted text-center">Depreciation of
                        {{ date('d-m-Y', strtotime(Request::post('start_date'))) }} to
                        {{ date('d-m-Y', strtotime(Request::post('end_date'))) }}</h4>
                    <h5 class="text-center text-muted mb-3">Total Depreciation: <strong>BDT
                            {{ number_format($allDepreciation->sum('amount')) }}</strong>
                    </h5>
                    <table class="table table-bordered" id="" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($allDepreciation as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        {{ $item->name }}
                                    </td>
                                    <td>
                                        {{ number_format($item->amount) }}
                                    </td>
                                    <td>
                                        {{ date('F Y', strtotime($item->date)) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>

    </div>
    <!-- End Page Content -->
@endsection
