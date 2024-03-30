@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('update.package') }}" method="POST" class="custom-validation" novalidate=""
                            autocomplete="off">
                            @csrf
                            <input type="hidden" name="id" value="{{ $package->id }}">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="md-3">
                                            <label for="example-text-input" class="col-sm-12 col-form-label">Package
                                                No</label>
                                            <input class="form-control" type="text" name="package_no"
                                                value="{{ $package->package_no }}" id="package_no" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="md-3">
                                            <label for="example-text-input" class="col-sm-12 col-form-label">Date</label>
                                            <input type="text" class="form-control date_picker" name="date"
                                                id="date" value="{{ $package->date }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="md-3">
                                            <label for="supplier_id" class="col-sm-12 col-form-label">Supplier
                                                Name</label>
                                            <select name="supplier_id" id="supplier_id" class="form-control form-select"
                                                required="" data-parsley-required-message="Supplier Id is required">
                                                <option selected value="">Select Supplier Name</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        {{ $package->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-5 table-responsive">
                                        <table class="table table-responsive table-striped">
                                            <thead class="bg-body">
                                                <tr>
                                                    <th width="20%" class="text-center">Package</th>
                                                    <th width="20%" class="text-center">Product</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-center">Unit Price</th>
                                                    <th class="text-center">Total</th>
                                                    <th class="text-center">
                                                        <button class="btn btn-success" type="button" onclick="cloneRow()">
                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                        </button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="tbody">
                                                @foreach ($package->packageMeta as $item)
                                                    <tr class="tr">
                                                        <td class="text-center">
                                                            <select name="package_type[]" id="package_1"
                                                                class="form-control form-select select2 category"
                                                                required=""
                                                                data-parsley-required-message="Category Id is required">
                                                                <option selected value="">Select Package Type</option>
                                                                <option value="Master"
                                                                    {{ $item->package_type === 'Master' ? 'selected' : '' }}>
                                                                    Master Packet</option>
                                                                <option value="Inner"
                                                                    {{ $item->package_type === 'Inner' ? 'selected' : '' }}>
                                                                    Inner Packet</option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <select name="product_id[]" id="product_id_1"
                                                                class="form-control form-select select2 product"
                                                                required=""
                                                                data-parsley-required-message="Product Id is required">
                                                                <option selected value="">Select Product</option>
                                                                @foreach ($products as $product)
                                                                    <option class="text-capitalize"
                                                                        value="{{ $product->id }}"
                                                                        {{ $product->id == $item->product_id ? 'selected' : '' }}>
                                                                        {{ $product->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control quantity"
                                                                placeholder="Quantity" name="quantity[]" id="quantity"
                                                                value="{{ $item->quantity }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control unit_price"
                                                                placeholder="Unit Price" name="unit_price[]" id="unit_price"
                                                                value="{{ $item->unit_price }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control total_amount"
                                                                placeholder="Total" readonly name="total_amount[]"
                                                                value="{{ $item->quantity * $item->unit_price }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" onclick="removeRow(event)"
                                                                class="btn btn-danger">
                                                                <i class="fa fa-times" aria-hidden="true"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4">
                                                        <label for="">Paid Amount</label>
                                                        <input type="text"class="form-control"
                                                            value="{{ $package->paid_amount }}" readonly>
                                                    </th>
                                                    <th>
                                                        <label for="">Total Amount</label>
                                                        <input type="text" readonly class="form-control"
                                                            name="estimated_total" id="estimated_total"
                                                            placeholder="Grand Total"
                                                            value="{{ $package->total_amount }}" readonly>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>

                                        <div class="row mb-3 mt-5">
                                            <div class="col-md-4">
                                                {{-- <label for="">Payment Status </label> --}}
                                                <select class="form-control" name="paid_status" id="paid_status"
                                                    required=""
                                                    data-parsley-required-message="Paid Status is required">
                                                    <option value="" selected disabled>Select Paid Status
                                                    </option>
                                                    <option value="full_paid">Full Paid</option>
                                                    <option value="partial_paid">Partial Paid</option>
                                                </select>
                                                <input type="text" placeholder="Enter Paid Amount"
                                                    class="form-control" name="paid_amount" id="paid_amount"
                                                    style="display:none;">
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
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-info" id="storeButton">Update
                                                        Purchase</button>
                                                </div>
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


    {{-- js --}}
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <script>
        $(document).ready(function() {

            $(document).on("keyup click", ".unit_price,.quantity", function() {
                let product_qty = $(this).closest('tr').find('input.quantity').val();
                let unit_price = $(this).closest('tr').find('input.unit_price').val();
                let total = unit_price * product_qty;
                console.log(total);
                $(this).closest('tr').find('input.total_amount').val(total);
                totalAmountOfPrice();
            });
        });
    </script>
    <script>
        let count = 2;

        function cloneRow() {
            let tr = `
            <tr class="tr">
                <td class="text-center">
                    <select name="package_type[]" id="package_${count}"
                        class="form-control form-select select2" required=""
                        data-parsley-required-message="Package type is required">
                        <option selected value="">Select Package Type</option>
                        <option value="Master">Master Packet</option>
                        <option value="Inner">Innner Packet</option>
                    </select>
                </td>
                <td class="text-center">
                    <select name="product_id[]" id="product_${count}"
                        class="form-control form-select select2">
                        <option selected value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="text-center">
                    <input type="text" class="form-control quantity" placeholder="Quantity"
                        name="quantity[]" id="quantity">
                </td>
                <td class="text-center">
                    <input type="text" class="form-control unit_price" placeholder="Unit Price"
                        name="unit_price[]" id="unit_price">
                </td>
                <td class="text-center">
                    <input type="text" class="form-control total_amount" placeholder="Total"
                        name="total_amount[]" id="total" readonly>
                </td>
                <td class="text-center">
                    <button type="button" onclick="removeRow(event)" class="btn btn-danger">
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
            $("#estimated_total").val(sum);
        }
    </script>



    <!-- paid status -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#paid_status').on('change', function() {
                let paidStatus = $(this).val();
                console.log('paidSource', paidStatus);
                if (paidStatus) {
                    $('#paid_source_col').show();
                    $('#vat_tax_col').show();
                }

                if (paidStatus == 'partial_paid') {
                    $('#paid_amount').show();
                } else {
                    $('#paid_amount').hide();
                }

                if (paidStatus == 'full_due') {
                    $('#paid_source_col').hide();
                }

            });


            $('#paid_source').on('change', function() {
                let paidSource = $(this).val();
                console.log('paidSource', paidSource);
                if (paidSource == 'bank') {
                    $('#bank-info').show();
                    $('#online-bank-row').hide();
                    $('#mobile-bank-info').hide();
                } else if (paidSource == 'online-banking') {
                    $('#bank-info').hide();
                    $('#online-bank-row').show();
                    $('#mobile-bank-info').hide();
                } else if (paidSource == 'mobile-banking') {
                    $('#bank-info').hide();
                    $('#online-bank-row').hide();
                    $('#mobile-bank-info').show();
                }
            });

            $('#vat_tax_field').on('change', function() {
                let vatTaxField = $(this).val();
                console.log('vat_tax_field', vatTaxField);
                if (vatTaxField == 'with-vat-tax') {
                    $('.vat').show();
                    $('.tax').show();
                } else {
                    $('.vat').hide();
                    $('.tax').hide();
                }
            });

            // new customer
            $('#company_id').on('change', function() {
                let compnayId = $(this).val();
                console.log(compnayId);
                if (compnayId == '0') {
                    $('#new_company').show();
                    $('#default_addBtn').hide();
                } else {
                    $('#new_company').hide();
                    $('#default_addBtn').show();
                }
            });
        });
    </script>
@endsection
