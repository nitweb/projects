@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ $title }}</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="{{ route('add.commission') }}">
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
                                        <th>Employee</th>
                                        <th>Commission</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Employee</th>
                                        <th>Commission</th>
                                        <th>Action</th>
                                    </tr>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($allCommission as $key => $commission)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $commission->employee->name }}
                                            </td>
                                            <td>
                                                {{ $commission->total }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a style="margin-left: 5px;"
                                                        href="{{ route('history.commission', $commission->employee_id) }}"
                                                        class="btn btn-info text-white" title="Show Commission History">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                        View History
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
