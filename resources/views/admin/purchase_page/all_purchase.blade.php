@extends('admin.admin_master')
@section('admin')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!-- Begin Page Content -->
    <div class="page-content">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-12 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">All Purchase</h6>
                        <h6 class="m-0 font-weight-bold text-primary">
                            <a href="{{ route('ingredient.purchase') }}">
                                <button class="btn btn-info">Add Purchase</button>
                            </a>
                        </h6>
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
                                <th>Purchase No</th>
                                <th>Total Amount</th>
                                <th>Paid Amount</th>
                                <th>Date</th>
                                <th>Due Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Sl</th>
                                <th>Purchase No</th>
                                <th>Total Amount</th>
                                <th>Paid Amount</th>
                                <th>Date</th>
                                <th>Due Amount</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($allPurchase as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td class="text-capitalize">
                                        {{ $item->purchase_no }}
                                    </td>
                                    <td class="text-capitalize">
                                        {{ number_format($item->total_amount) }}
                                    </td>
                                    <td class="text-capitalize">
                                        {{ number_format($item->paid_amount) }}
                                    </td>
                                    <td class="text-capitalize">
                                        {{ date('d-M-Y', strtotime($item->date)) }}
                                    </td>
                                    <td>
                                        {{ number_format($item->due_amount) }}
                                    </td>
                                    <td style="display:flex">
                                        <a href="{{ route('edit.purchase', $item->id) }}" class="btn btn-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('delete.purchase', $item->id) }}" class="btn btn-danger"
                                            id="delete" title="Purchase Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                        <a href="{{ route('view.purchase', $item->id) }}" class="btn btn-success"
                                            title="View Details">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                        <a title="Purchase Print " style="margin-left: 5px;"
                                        href="{{ route('purchase.print', $item->id) }}" class="btn btn-success">
                                        <i class="fa fa-print" aria-hidden="true"></i>
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
