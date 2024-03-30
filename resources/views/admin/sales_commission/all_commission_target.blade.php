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
                                        <th>Sales Target</th>
                                        <th>Commission</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Employee</th>
                                        <th>Category</th>
                                        <th>Sales Target</th>
                                        <th>Commission</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($allCommissionTarget as $key => $history)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $history->employee->name }}
                                            </td>
                                            <td>
                                                {{ $history->category->name }}
                                            </td>
                                            <td>
                                                {{ number_format($history->sales_target) }}
                                            </td>
                                            <td>
                                                {{ $history->sales_commission }}%
                                            </td>
                                            <td>
                                                {{ date('d-M,Y', strtotime($history->created_at)) }}
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a style="margin-left: 5px;"
                                                        href="{{ route('edit.commission', $history->id) }}"
                                                        class="btn btn-info text-white" title="Edit Commission">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a style="margin-left: 5px;"
                                                        href="{{ route('delete.commission', $history->id) }}"
                                                        class="btn btn-danger text-white" id="delete"
                                                        title="Delete Commission">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                    <a style="margin-left: 5px;"
                                                        href="{{ route('deactive.commission', $history->id) }}"
                                                        class="btn btn-dark text-white" id="deactiveTarget"
                                                        title="Disable Commission">
                                                        <i class="fas fa-thumbs-down    "></i>
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
