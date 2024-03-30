@extends('admin.admin_master')
@section('admin')
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Add Ingredient </h4><br><br>


                            @if (count($errors))
                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ $error }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </p>
                                @endforeach
                            @endif


                            <form method="post" action="{{ route('ingredient.store') }}" id="myForm" autocomplete="off">
                                @csrf

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Ingredient
                                        Name</label>
                                    <div class="col-sm-10 form-group">
                                        <input name="name" placeholder="Ingredient Name"
                                            class="form-control @error('name') is-invalid @enderror" type="text"
                                            id="name" value="{{ old('name') }}">
                                    </div>
                                </div>
                                <!-- end row -->

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Unit Name</label>
                                    <div class="col-sm-10 form-group">
                                        <select name="unit_id" id="unit_id" class="form-control">
                                            <option value="">Select Unit</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}"
                                                    {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->short_form }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- end row -->


                                <input type="submit" class="btn btn-info waves-effect waves-light" value="Add ingredient">
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
                    unit_id: {
                        required: true,
                    },
                    supplier_id: {
                        required: true,
                    },
                    weight: {
                        required: true,
                    },
                },
                message: {
                    name: {
                        required: "Please Enter ingredient name",
                    },
                    category_id: {
                        required: "Please Enter category name",
                    },
                    unit_id: {
                        quantity: "Please Select Unit",
                    },
                    supplier_id: {
                        quantity: "Please Select Supplier",
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
