@extends('admin.admin_master')
@section('admin')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Begin Page Content -->
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-10 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-muted">Update permission</h4>
                            <form class="custom-validation" action="{{ route('update.permission') }}" method="POST"
                                novalidate="" id="form" autocomplete="off">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        <input type="hidden" name="id" value="{{ $permissionInfo->id }}">
                                        <div class="mb-2">
                                            <input type="text" id="name" name="name" class="form-control"
                                                placeholder="Permission Name" required=""
                                                data-parsley-required-message="Permission Name is required"
                                                value="{{ $permissionInfo->name }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <div class="mb-2">
                                            <select name="group_name" id="group_name" class="form-control" required
                                                data-parsley-required-message="Group Name is required">
                                                <option disabled selected>Select Group Name</option>
                                                <option value="employee"
                                                    {{ $permissionInfo->group_name == 'employee' ? 'selected' : '' }}>
                                                    Employee</option>
                                                <option value="employee-salary"
                                                    {{ $permissionInfo->group_name == 'employee-salary' ? 'selected' : '' }}>
                                                    Employee Salary</option>
                                                <option value="role"
                                                    {{ $permissionInfo->group_name == 'role' ? 'selected' : '' }}>Role
                                                </option>
                                                <option value="admin"
                                                    {{ $permissionInfo->group_name == 'admin' ? 'selected' : '' }}>Admin
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-0 mt-3">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                                        Update Permission
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#form').parsley();
    </script>
@endsection
