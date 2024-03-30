@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('battery.store') }}" method="POST" class="custom-validation" novalidate=""
                            autocomplete="off">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="md-3">
                                        <label for="example-text-input" class="col-sm-12 col-form-label">Battery
                                            Category</label>
                                        <input type="text" class="form-control" name="category_id" id="category_id"
                                            value="{{ $batteryInfo->categoryForBattery->name }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="md-3">
                                        <label for="example-text-input" class="col-sm-12 col-form-label">Battery
                                            Name</label>
                                        <input type="text" class="form-control" name="category_id" id="category_id"
                                            value="{{ $batteryInfo->productForBattery->name . ' ' . $batteryInfo->productForBattery->weight . 'KG' }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-end">
                                        <label for="example-text-input" class="col-sm-12 col-form-label invisible">Back To Previous</label>
                                        <a href="{{ URL::previous() }}" class="btn btn-info">
                                            <i class="fa fa-arrow-left"></i> Back
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-5 table-responsive">
                                    <table class="table border table-responsive table-striped">
                                        <thead class="bg-body">
                                            <tr>
                                                <th class="text-center">Ingredient</th>
                                                <th class="text-center">Quantity with Wastage</th>
                                                <th class="text-center">Wastage</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody">
                                            @foreach ($batteryDetails as $details)
                                                <tr class="tr">
                                                    <td class="text-center">
                                                        <input type="text" class="form-control qty"
                                                            placeholder="Quantity with Wastage" name="quantity[]"
                                                            value="{{ $details->ingredient->name }}" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control qty"
                                                            placeholder="Quantity with Wastage" name="quantity[]"
                                                            value="{{ $details->quantity . 'KG' }}" readonly>

                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control wastage"
                                                            placeholder="Wastage" name="wastage[]"
                                                            value="{{ $details->wastage . 'KG' }}" readonly>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
