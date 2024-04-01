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
                                        <label for="example-text-input" class="col-sm-12 col-form-label">Category </label>
                                        <select name="category_id" id="category_1" class="form-control form-select category"
                                            required="" data-parsley-required-message="Category Id is required">
                                            <option selected value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="md-3">
                                        <label for="example-text-input" class="col-sm-12 col-form-label">Product Name
                                        </label>
                                        <select name="product_id" id="product_1" class="form-control form-select product"
                                            required="" data-parsley-required-message="Product Id is required">
                                            <option selected value="">Select Product</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-5 table-responsive">
                                    <table class="table border table-responsive table-striped">
                                        <thead class="bg-body">
                                            <tr>
                                                <th class="text-center">Ingredient</th>
                                                <th class="text-center">Quantity with Wastage</th>
                                                <th class="text-center">Wastage</th>
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
                                                    <select name="ingredient_id[]" id="ingredient_1"
                                                        class="form-control form-select ingredient" required=""
                                                        data-parsley-required-message="Ingredient is required">
                                                        <option selected value="">Select Ingredient</option>
                                                        @foreach ($ingredients as $ingredient)
                                                            <option value="{{ $ingredient->id }}">
                                                                {{ $ingredient->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control qty" id="qty_1"
                                                        placeholder="Quantity with Wastage" name="quantity[]">

                                                </td>
                                                <td>
                                                    <input type="text" class="form-control wastage" id="wastage_1"
                                                        placeholder="Wastage" name="wastage[]">
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" onclick="removeRow(event)"
                                                        class="btn btn-danger">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        {{-- <tfoot>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td>
                                                    <select name="discount_type" class="form-control" id="discount_type">
                                                        <option value="">Select Disocunt Type</option>
                                                        <option value="flat">Flat</option>
                                                        <option value="percentage">Percentage</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="discount_rate" id="discount_rate"
                                                        class="form-control discount_rate" placeholder="Discount Amount">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th colspan="2"> </th>
                                                <th class="text-end" width="20%"> Discount Amount</th>
                                                <th>
                                                    <input type="text" readonly class="form-control"
                                                        name="discount_amount" id="discount_amount" value="0">
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="2"> </th>
                                                <th class="text-end"> Grand Total</th>
                                                <th>
                                                    <input type="text" readonly class="form-control"
                                                        name="estimated_total" id="estimated_total"
                                                        placeholder="Grand Total" value="0" min="0">

                                                    <input type="hidden" class="form-control" placeholder="Total"
                                                        name="total_quantity" id="total_quantity" readonly>
                                                </th>
                                            </tr>
                                        </tfoot> --}}
                                    </table>

                                    <div class="row mb-3 mt-5">
                                        {{-- <div class="col-md-4">
                                            <label for="">Payment Status </label>
                                            <select class="form-control" name="paid_status" id="paid_status"
                                                required="" data-parsley-required-message="Paid Status is required">
                                                <option value="" selected disabled>Select Paid Status
                                                </option>
                                                <option value="full_paid">Full Paid</option>
                                                <option value="full_due">Full Due</option>
                                                <option value="partial_paid">Partial Paid</option>
                                            </select>
                                            <input type="text" placeholder="Enter Paid Amount" class="form-control"
                                                name="paid_amount" id="paid_amount" style="display:none;">
                                        </div>
                                        <div class="col-md-4" id="paid_source_col" style="display: none;">
                                            <select class="form-control select2" name="bank_id" id="bank_id">
                                                <option value="">Select Payment Status</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                                @endforeach
                                            </select>

                                            <div class="row" id="transaction_note" style="display: none;">
                                                <div class="col-12">
                                                    <input type="text" placeholder="Note" class="form-control"
                                                        name="note" id="note">
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="col-4">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-info" id="storeButton">Battery
                                                    Store</button>
                                            </div>
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

    <script>
        let count = 2;

        function cloneRow() {
            const tr = `
            <tr class="tr">
                <td class="text-center">
                    <select name="ingredient_id[]" id="ingredient_${count}"
                        class="form-control form-select" required=""
                        data-parsley-required-message="Ingredient is required">
                        <option selected value="">Select Ingredient</option>
                        @foreach ($ingredients as $ingredient)
                            <option value="{{ $ingredient->id }}">
                                {{ $ingredient->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control qty" id="qty_${count}" placeholder="Quantity with Wastage" name="quantity[]">
                </td>
                <td>
                    <input type="text" class="form-control wastage" id="wastage_${count}" placeholder="Wastage" name="wastage[]">
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


        $(document).on("change", ".category", function() {
            const id = $(this).find('option:selected').val();

            const supplier_id = $("#supplier_id").val();
            let dataId = $(this).attr('id');
            let num = dataId.split('_');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/get/product',
                type: "post",
                data: {
                    id: id,
                },

                success: function(data) {

                    let html = '<option value="">Select Product </option>';
                    $.each(data, function(key, product) {
                        html +=
                            `<option value="${product.id}">
                                ${product.name} ${product.weight != null ? product.weight.concat(' KG') : ''}
                            </option>`;
                    });
                    $("#product_" + num[1]).html(html);
                }
            });
        });
    </script>


    <script>
        $(document).on("change keyup", ".qty", function() {
            const id = $(this).closest('tr').find('option:selected').val();
            let qtyVal = $(this).closest('tr').find('.qty').val();


            let wastageId = $(this).closest('tr').find('.wastage').attr('id');
            let wastageNum = wastageId.split('_');
            let wastage = qtyVal * 5 / 100;

            $("#wastage_" + wastageNum[1]).val(wastage);
        });
    </script>


    <script>
        // calculate sum of amount
        function totalAmountOfPrice() {
            let sum = 0;
            $('.total_amount').each(function() {
                let value = $(this).val();
                if (!isNaN(value) && value.length != 0) {
                    sum += parseFloat(value);
                }
            });
            console.log(sum);

            let discount_type = $("#discount_type").val();
            console.log(discount_type);

            let discount_rate = parseFloat($('#discount_rate').val());
            let discount_amount = 0;
            console.log(discount_rate);
            if (!isNaN(discount_rate) && discount_rate.length != 0) {
                if (discount_type == 'flat') {
                    sum -= parseFloat(discount_rate);
                    discount_amount = parseFloat(discount_rate);

                } else if (discount_type == 'percentage') {
                    let percentageAmount = Math.round((sum * discount_rate) / 100);
                    discount_amount = percentageAmount;
                    sum -= parseFloat(percentageAmount);
                }
            }
            $("#discount_amount").val(discount_amount);
            $("#estimated_total").val(Math.round(sum));
        }


        $(document).on('keyup click change', '#discount_type', function() {
            totalAmountOfPrice();
        });

        $(document).on('keyup', '#discount_rate', function() {
            totalAmountOfPrice();
        });
    </script>
@endsection
