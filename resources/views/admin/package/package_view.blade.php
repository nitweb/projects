@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="md-3">
                                    <label for="example-text-input" class="col-sm-12 col-form-label">Package No</label>
                                    <input class="form-control" type="text" name="purchase_no"
                                        value="{{ $package->package_no }}" id="purchase_no" readonly disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="md-3">
                                    <label for="example-text-input" class="col-sm-12 col-form-label">Date</label>
                                    <input type="text" class="form-control" disabled name="date" id="date"
                                        value="{{ $package->date }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="md-3">
                                    <label for="supplier_id" class="col-sm-12 col-form-label">Supplier
                                        Name</label>
                                    <input type="text" class="form-control" name="supplier_id" id="supplier_id"
                                        value="{{ $package->supplier->name }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-12 mt-5">
                                <table class="table table-responsive table-striped">
                                    <thead class="bg-body">
                                        <tr>
                                            <th class="text-center">Package Type</th>
                                            <th class="text-center">Product</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Unit Price</th>
                                            <th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        @foreach ($package->packageMeta as $item)
                                            <tr class="tr">
                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="category_id"
                                                        id="category_id" value="{{ $item->package_type }} Packet" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="product_id"
                                                        id="product_id" value="{{ $item->product->name }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control quantity"
                                                        placeholder="Quantity" name="quantity[]" id="quantity"
                                                        value="{{ $item->quantity }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control unit_price"
                                                        placeholder="Unit Price" name="unit_price[]" id="unit_price"
                                                        value="{{ $item->unit_price }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control total_amount"
                                                        placeholder="Total" readonly
                                                        value="{{ $item->quantity * $item->unit_price }}" disabled>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4">
                                                <label for="">Paid Amount</label>
                                                <input type="text" name="paid_amount" id="paid_amount"
                                                    placeholder="Enter Paid Amount" class="form-control"
                                                    value="{{ $package->paid_amount }}" disabled>
                                            </th>
                                            <th>
                                                <label for="">Total Amount</label>
                                                <input type="text" readonly class="form-control" name="estimated_total"
                                                    id="estimated_total" placeholder="Grand Total"
                                                    value="{{ $package->total_amount }}" disabled>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>

                                <div class="form-group mt-5">

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
