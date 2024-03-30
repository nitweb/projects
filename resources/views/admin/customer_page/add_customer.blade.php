@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-10 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="text-muted">Add Customer</h2>
                            <form method="POST" class="custom-validation" action="{{ route('store.customer') }}"
                                novalidate="">
                                @csrf
                                <div class="mb-3 mt-3">
                                    <input type="text" id="name" name="name" class="form-control" required=""
                                        placeholder="Customer Name"
                                        data-parsley-required-message="Customer Name is required">
                                </div>

                                <div class="mb-3">
                                    <div>
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder="Enter a valid email">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div>
                                        <input type="tel" id="phone" name="phone" class="form-control"
                                            required="" placeholder="Phone Number"
                                            data-parsley-required-message="Phone Number is required.">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div>
                                        <input type="tel" id="telephone" name="telephone" class="form-control"
                                            placeholder="Telephone Number">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <select name="employee_id" id="employee_id" class="form-control select2">
                                        <option value="" selected>Select Under Employee</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }} -
                                                {{ $employee->phone }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="mb-3">
                                    <div>
                                        <textarea required="" data-parsley-required-message="Address is required." name="address" id="address"
                                            class="form-control" rows="5" placeholder="Enter Your Customer Address"></textarea>
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <div>
                                        <button type="submit" class="btn btn-info waves-effect waves-light me-1">
                                            Add Customer
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
