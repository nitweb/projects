@extends('admin.admin_master')
@section('admin')
    <!-- Begin Page Content -->
    <div class="page-content">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-12 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">All {{ $title }}</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Customer Name</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Sl</th>
                                <th>Customer Name</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($customers as $key => $customer)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td class="text-capitalize">
                                        {{ $customer->name }}
                                    </td>
                                    <td>
                                        @php
                                            $replacement = App\Models\ReturnProduct::where('customer_id', $customer->id)
                                                ->where('replace_status', '0')
                                                ->sum('quantity');
                                        @endphp
                                        {{ $replacement }} Pcs
                                    </td>
                                    <td>
                                        <a href="{{ route('customer.replace.history', $customer->id) }}"
                                            class="btn btn-info">
                                            <i class="fa fa-eye" aria-hidden="true"></i> Replace History
                                        </a>
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
