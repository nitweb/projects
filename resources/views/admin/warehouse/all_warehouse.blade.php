@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ $title }}</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="{{ route('add.warehouse') }}">
                    <button class="btn btn-info"> <i class="fa fa-plus-circle" aria-hidden="true"> Add {{ $title }}</i>
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
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($allWarehouse as $key => $warehouse)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $warehouse->name }}
                                            </td>
                                            <td>
                                                {{ $warehouse->email }}
                                            </td>
                                            <td>
                                                {{ $warehouse->phone }}
                                            </td>
                                            <td>
                                                {{ $warehouse->address }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a style="margin-left: 5px;"
                                                        href="{{ route('edit.warehouse', $warehouse->id) }}"
                                                        class="btn btn-info text-white" title="Edit Warehouse">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a style="margin-left: 5px;"
                                                        href="{{ route('delete.warehouse', $warehouse->id) }}"
                                                        class="btn btn-danger text-white" id="delete"
                                                        title="Delete Warehouse">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                    <a style="margin-left: 5px;"
                                                        href="{{ route('warehouse.stock.history', $warehouse->id) }}"
                                                        class="btn btn-success text-white" title="Stock History">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                        Stock History
                                                    </a>
                                                </div>
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
