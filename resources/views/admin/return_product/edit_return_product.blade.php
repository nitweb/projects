@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h3>Update Replace Product</h3>
                        <form action="{{ route('update.replace.product') }}" method="POST" class="custom-validation"
                            novalidate="">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" value="{{ $returnProduct->id }}" name="id">
                                    <div class="mb-3">
                                        <label for="return_no" class="col-sm-12 col-form-label">Replace No</label>
                                        <input class="form-control" type="text" name="return_no"
                                            value="{{ $returnProduct->return_no }}" id="return_no" readonly>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="example-text-input" class="col-sm-12 col-form-label">Date</label>
                                        <input type="date" class="form-control date_picker" name="date" id="date"
                                            required="" data-parsley-required-message="Purchase Date is required"
                                            value="{{ $returnProduct->date }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="customer_id" class="col-sm-12 col-form-label">Customer
                                            Name</label>
                                        <select name="customer_id" id="customer_id" class="form-control form-select select2"
                                            required="" data-parsley-required-message="Customer Id is required">
                                            <option selected value="">Select Customer Name</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    {{ $returnProduct->customer_id == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="customer_id" class="col-sm-12 col-form-label">Product
                                            Name</label>
                                        <select name="product_id" id="product_id" class="form-control form-select select2"
                                            required="" data-parsley-required-message="Customer Id is required">
                                            <option selected value="">Select Product Name</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ $returnProduct->product_id == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="quantity" class="col-sm-12 col-form-label">Product
                                            Quantity</label>
                                        <input type="text" class="form-control" name="quantity" id="quantity"
                                            placeholder="Enter Quantity" value="{{ $returnProduct->quantity }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-info waves-effect waves-light me-1">
                                            Update Replace Product
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
