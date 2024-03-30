@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All {{ $title }} Supplier</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="{{ route('supplier.add') }}">
                    <button class="btn btn-info"> <i class="fa fa-plus-circle" aria-hidden="true"> Add Supplier</i> </button>
                </a>
            </h6>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Name</th>
                                        <th>Info</th>
                                        <th>Type</th>
                                        <th>Due</th>
                                        <th>Replacement</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Name</th>
                                        <th>Info</th>
                                        <th>Type</th>
                                        <th>Due</th>
                                        <th>Replacement</th>
                                        <th>Action</th>
                                    </tr>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($supplierAll as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $item->name }}
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $item->mobile_no }}</p>
                                                <p class="mb-0">{{ $item->email }}</p>
                                                <p class="mb-0">{{ $item->address }}</p>
                                            </td>
                                            <th>
                                                @if ($item->status == '0')
                                                    <span class="badge bg-info">Packet Supplier</span>
                                                @else
                                                    <span class="badge bg-info">Product Supplier</span>
                                                @endif
                                            </th>
                                            <td>
                                                BDT {{ number_format($item->total_due) }}
                                            </td>
                                            <td>
                                                @php
                                                    $replacement = App\Models\SupplierReplaceHistory::where(
                                                        'supplier_id',
                                                        $item->id,
                                                    )
                                                        ->where('status', '0')
                                                        ->sum('quantity');
                                                @endphp
                                                {{ $replacement }}
                                            </td>
                                            <td>
                                                <a style="margin-left: 5px;" href="{{ route('supplier.edit', $item->id) }}"
                                                    class="btn btn-info">
                                                    <i class="fas fa-edit    "></i>
                                                </a>
                                                <a id="delete" style="margin-left: 5px;"
                                                    href="{{ route('supplier.delete', $item->id) }}"
                                                    class="btn btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>

                                                <a style="margin-left: 5px;"
                                                    href="{{ route('supplier.all.purchase', $item->id) }}"
                                                    class="btn btn-primary">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                    View Purchase
                                                </a>
                                                <a style="margin-left: 5px;"
                                                    href="{{ route('supplier.account.details', $item->id) }}"
                                                    class="btn btn-success text-white" title="Purchase History">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                    Purchase History
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
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="{{ asset('backend/assets/js/code.js') }}"></script>
@endsection
