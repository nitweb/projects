@extends('admin.admin_master')
@section('admin')
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Supplier Add Page </h4><br><br>


                            @if (count($errors))
                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger alert-dismissible fade show"> {{ $error }} </p>
                                @endforeach
                            @endif


                            <form method="post" action="{{ route('supplier.update') }}" id="myForm">
                                @csrf
                                <input type="hidden" name="id" id="id" value="{{ $supplierInfo->id }}">
                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Supplier Name</label>
                                    <div class="col-sm-10 form-group">
                                        <input name="name" class="form-control" type="text" id="name"
                                            value="{{ $supplierInfo->name }}">
                                    </div>
                                </div>
                                <!-- end row -->

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Supplier Email</label>
                                    <div class="col-sm-10 form-group">
                                        <input name="email" class="form-control" type="email" id="email"
                                            value="{{ $supplierInfo->email }}">
                                    </div>
                                </div>
                                <!-- end row -->


                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Supplier Mobile</label>
                                    <div class="col-sm-10 form-group">
                                        <input name="mobile_no" class="form-control" type="tel" id="mobile_no"
                                            value="{{ $supplierInfo->mobile_no }}">
                                    </div>
                                </div>
                                <!-- end row -->


                                <div class="row mb-3">
                                    <label for="supplier_type" class="col-sm-2 col-form-label">Supplier Mobile</label>
                                    <div class="col-sm-10 form-group">
                                        <select name="supplier_type" class="form-control" id="supplier_type" required>
                                            <option disabled selected>Select Supplier Type</option>
                                            <option value="1" {{ $supplierInfo->status == '1' ? 'selected' : '' }}>
                                                Product Supplier</option>
                                            <option value="0" {{ $supplierInfo->status == '0' ? 'selected' : '' }}>Package Supplier</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- end row -->

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Supplier Address</label>
                                    <div class="col-sm-10 form-group">
                                        <input name="address" class="form-control" type="text" id="address"
                                            value="{{ $supplierInfo->address }}">
                                    </div>
                                </div>
                                <!-- end row -->


                                <input type="submit" class="btn btn-info waves-effect waves-light" value="Update Supplier">
                            </form>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div>



        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#myForm").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    mobile_no: {
                        required: true,
                    },
                    email: {
                        required: true,
                    },
                    address: {
                        required: true,
                    },
                },
                message: {
                    name: {
                        required: "Please Enter your name",
                    },
                    email: {
                        required: "Please Enter your email address",
                    },
                    mobile_no: {
                        required: "Please Enter your mobile number",
                    },
                    address: {
                        required: "Please Enter your address",
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-faedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
            });
        });
    </script>


@endsection
