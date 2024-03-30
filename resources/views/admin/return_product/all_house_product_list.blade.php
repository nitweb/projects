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
                        <h6 class="m-0 font-weight-bold text-primary">
                            <a href="{{ route('add.replace.product') }}">
                                <button class="btn btn-info">Add Replace Product</button>
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
                                <th>Return No</th>
                                <th>Product Name</th>
                                <th>Customer Name</th>
                                <th>Quantity</th>
                                <th>Return Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Sl</th>
                                <th>Return No</th>
                                <th>Product Name</th>
                                <th>Customer Name</th>
                                <th>Quantity</th>
                                <th>Return Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($returnAll as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td class="text-capitalize">
                                        {{ $item->return_no }}
                                    </td>
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
                                        {{ date('d F Y', strtotime($item->date)) }}
                                    </td>
                                    <td class="text-capitalize">
                                        @if ($item->status == '0')
                                            <span class="badge bg-danger">In House</span>
                                        @endif
                                    </td>
                                    <td>

                                        <a href="{{ route('edit.replace.product', $item->id) }}" class="btn btn-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('delete.replace.product', $item->id) }}" class="btn btn-danger"
                                            id="delete" title="Replace Product Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                        @if ($item->status == '0' && $key == '0')
                                            {{-- <form class="d-inline"
                                                action="{{ route('store.replace.supplier', $item->id) }}" method="GET">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                                <input type="hidden" name="return_id" value="{{ $item->id }}">
                                                <button class="btn btn-dark" id=""><i class="fa fa-paper-plane"
                                                        aria-hidden="true"></i>
                                                    Send To Supplier</button>
                                            </form> --}}

                                            <a href="{{ route('store.replace.supplier', $item->id) }}" class="btn btn-dark"
                                                id="sendSupplier" title="Send To Supplier">
                                                <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                                Send To Supplier
                                            </a>
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
