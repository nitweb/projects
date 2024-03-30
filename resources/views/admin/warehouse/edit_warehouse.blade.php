@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-muted">{{ $title }}</h4>
                            <form method="POST" class="custom-validation" action="{{ route('update.warehouse') }}"
                                novalidate="">
                                @csrf
                                <input type="hidden" name="id" value="{{ $warehouseInfo->id }}">
                                <div class="mb-3 mt-3">
                                    <input type="text" id="name" name="name" class="form-control"
                                        value="{{ $warehouseInfo->name }}" required="" placeholder="Warehouse Name"
                                        data-parsley-required-message="Warehouse Name is required">
                                </div>


                                <div class="mb-3">
                                    <div>
                                        <input type="tel" id="phone" name="phone" class="form-control"
                                            required="" placeholder="Phone Number"
                                            data-parsley-required-message="Phone Number is required."
                                            value="{{ $warehouseInfo->phone }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div>
                                        <input type="email" id="email" name="email" class="form-control"
                                            placeholder="Email" value="{{ $warehouseInfo->email }}">
                                    </div>
                                </div>


                                <div class="mb-3">
                                    <div>
                                        <textarea required="" data-parsley-required-message="Address is required." name="address" id="address"
                                            class="form-control" rows="5" placeholder="Enter Your Address">{{ $warehouseInfo->address }}</textarea>
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <div>
                                        <button type="submit" class="btn btn-info waves-effect waves-light me-1">
                                            Update Warehouse
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
