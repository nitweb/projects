@extends('admin.admin_master')
@section('admin')
    <!-- Begin Page Content -->
    <div class="page-content">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-12 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="m-0 font-weight-bold text-primary">{{ $customer->name }}</h6>
                                <p class="m-0">{{ $customer->email }}</p>
                                <p class="m-0">{{ $customer->mobile_no }}</p>
                                <p class="m-0 ">customer Product Replacement History</p>
                            </div>
                            <h6 class="m-0 font-weight-bold text-primary">
                                <a href="{{ url()->previous() }}">
                                    <button class="btn btn-dark"> <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                        Back</button>
                                </a>
                            </h6>
                        </div>
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
                                <th>Product Name</th>
                                <th>Customer Name</th>
                                <th>Quantity</th>
                                <th>Return Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Sl</th>
                                <th>Product Name</th>
                                <th>Customer Name</th>
                                <th>Quantity</th>
                                <th>Return Date</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($replaceHistory as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td class="text-capitalize">
                                        {{ $item->product->name }}
                                    </td>
                                    <td class="text-capitalize">
                                        {{ $item->customer->name }}
                                    </td>
                                    <td>
                                        {{ $item->quantity }} {{ $item->product->unit->short_form }}
                                    </td>
                                    <td class="text-capitalize">
                                        {{ date('d F, Y', strtotime($item->date)) }}
                                    </td>
                                    <td>
                                        @if ($item->status == '0')
                                            <span class="badge bg-danger">In House</span>
                                        @elseif($item->status == '1')
                                            <span class="badge bg-info">Supplier House</span>
                                        @elseif($item->status == '2')
                                            <span class="badge bg-success">Replaced</span>
                                        @endif
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
