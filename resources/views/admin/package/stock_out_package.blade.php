@extends('admin.admin_master')
@section('admin')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <div class="page-content">
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('store.stock.out') }}" method="POST" class="custom-validation" novalidate="">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="md-3">
                                        <label for="warehouse_no" class="col-sm-12 col-form-label">Stock Out No</label>
                                        <input class="form-control" type="text" name="warehouse_no"
                                            value="{{ $warehouse_no }}" id="warehouse_no" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="md-3">
                                        <label for="date" class="col-sm-12 col-form-label">Date</label>
                                        <input type="date" class="form-control date_picker" name="date" id="date"
                                            required="" data-parsley-required-message="Purchase Date is required">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="md-3">
                                        <label for="warehouse_id" class="col-sm-12 col-form-label">Warehouse
                                            Name</label>
                                        <select name="warehouse_id" id="warehouse_id"
                                            class="form-control form-select select2" required=""
                                            data-parsley-required-message="Warehouse Id is required">
                                            <option selected value="">Select Warehouse Name</option>
                                            @foreach ($allWarehouse as $warehouse)
                                                <option value="{{ $warehouse->id }}">
                                                    {{ $warehouse->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mt-5">
                                    <table class="table border table-responsive table-striped">
                                        <thead class="bg-body">
                                            <tr>
                                                <th class="text-center">Catoon Type</th>
                                                <th class="text-center">Product</th>
                                                <th class="text-center">Current Stock</th>
                                                <th class="text-center">Quantity Out</th>
                                                <th class="text-center">
                                                    <button class="btn btn-success" type="button" onclick="cloneRow()">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody">
                                            <tr class="tr">
                                                <td class="text-center">
                                                    <select name="package_type[]" id="package_1"
                                                        class="form-control form-select package_type" required=""
                                                        data-parsley-required-message="Package type is required">
                                                        <option selected value="">Select Package Type</option>
                                                        <option value="Master">Master Packet</option>
                                                        <option value="Inner">Innner Packet</option>
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <select name="product_id[]" id="product_1"
                                                        class="form-control form-select product" required=""
                                                        data-parsley-required-message="Product Id is required">
                                                        <option selected value="">Select Product</option>
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control stock"
                                                        placeholder="Stock Quantity" name="stock_qty[]" id="stock_qty_1"
                                                        readonly>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control quantity"
                                                        placeholder="Quantity" name="selling_qty[]" id="selling_qty"
                                                        required=""
                                                        data-parsley-required-message="Quantity Id is required">
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" onclick="removeRow(event)"
                                                        class="btn btn-danger">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row mb-3 mt-4">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-info" id="storeButton">Submit</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    {{-- js --}}


    {{-- <script>
        $(document).on("mouseleave", ".quantity", function() {
            let product_stock = $(this).closest('tr').find('input.stock').val();
            let selling_qty = $(this).closest('tr').find('input.quantity').val();
            console.log('stock', product_stock, 'qty', selling_qty);

            if (selling_qty > product_stock) {
                $.notify("Product Stock not sufficient", {
                    globalPosition: 'top right',
                    className: 'error'
                });
                return false;
            }
        });
    </script> --}}

    {{-- <script>
        $(document).ready(function() {

            $(document).on("keyup", ".quantity", function() {
                let product_stock = $(this).closest('tr').find('input.stock').val();
                let selling_qty = $(this).closest('tr').find('input.quantity').val();
                console.log('stock', product_stock, 'qty', selling_qty);

                if (selling_qty > product_stock) {
                    $.notify("Product Stock not sufficient", {
                        globalPosition: 'top right',
                        className: 'error'
                    });
                    return false;
                }
            });
        });
    </script> --}}


    <script>
        let count = 2;

        function cloneRow() {
            const tr = `
            <tr class="tr">
                <td class="text-center">
                    <select name="package_type[]" id="package_${count}"
                        class="form-control form-select select2 package_type" required=""
                        data-parsley-required-message="Package type is required">
                        <option selected value="">Select Package Type</option>
                        <option value="Master">Master Packet</option>
                        <option value="Inner">Innner Packet</option>
                    </select>
                </td>
                <td class="text-center">
                    <select name="product_id[]" id="product_${count}"
                        class="form-control form-select select2 product" required=""
                        data-parsley-required-message="Product Id is required">
                        <option selected value="">Select Product</option>
                    </select>
                </td>
                <td class="text-center">
                        <input type="text" class="form-control stock"
                            placeholder="Stock Quantity" name="stock_qty[]" id="stock_qty_${count}" readonly>
                </td>
                <td class="text-center">
                    <input type="text" class="form-control quantity"
                        placeholder="Quantity" name="selling_qty[]" id="selling_qty"
                        required=""
                        data-parsley-required-message="Quantity Id is required">
                </td>
                <td class="text-center">
                    <button type="button" onclick="removeRow(event)"
                        class="btn btn-danger">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>

            `;
            $('.tbody').append(tr);
            count++;
        }



        function removeRow(event) {
            if ($('.tr').length > 1) {
                $(event.target).closest('.tr').remove();
                totalAmountOfPrice();
            }
        }


        $(document).on("change", ".package_type", function() {
            const id = $(this).closest('tr').find('option:selected').val();

            let dataId = $(this).attr('id');
            let num = dataId.split('_');
            console.log('type', id);

            $.ajax({
                type: 'GET',
                url: "{{ route('get.package.product', '') }}" + "/" + id,
                success: function(data) {
                    console.log(data);

                    let html = '<option value="">Select Product </option>';
                    $.each(data, function(key, product) {
                        html +=
                            `<option value="${product.product_id}">${product.product_name}</option>`;
                    });
                    $("#product_" + num[1]).html(html);
                }
            });
        });
    </script>
    <script>
        $(document).on("change", ".product", function() {
            const type = $(this).closest('tr').find('option:selected').val();
            const id = $(this).val();
            let dataId = $(this).attr('id');
            let num = dataId.split('_');
            $.ajax({
                data: {
                    product_type: type,
                },
                type: 'GET',
                url: "{{ route('get.package.stock', '') }}" + "/" + id,
                success: function(data) {
                    console.log('data', data);

                    let stock = 0;
                    $.each(data, function(key, productStock) {
                        stock += parseFloat(`${productStock.quantity}`);
                    })
                    $("#stock_qty_" + num[1]).val(stock);
                }
            });
        });
    </script>
@endsection
