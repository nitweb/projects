@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" class="custom-validation" action="{{ route('store.expense') }}" novalidate=""
                            autocomplete="off">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Expense Purpose</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <select name="expense_head" id="expense_head" class="form-control" required
                                        data-parsley-required-message="Expense Purpose is required." aria-readonly="true">
                                        <option value="">Select Expense Purpose</option>
                                        <option value="House Rent">House Rent</option>
                                        <option value="Salary">Salary</option>
                                        <option value="Utility">Utility Bill</option>
                                        <option value="Snacks">Snacks</option>
                                        <option value="Other">Others</option>
                                    </select>
                                    <input style="display: none;" type="text" name="others"
                                        placeholder="Enter Your Purpose" class="form-control others">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Expense Description</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" name="description" id="description" class="form-control"
                                        placeholder="Description">
                                </div>
                            </div>


                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Amount</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    @error('amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <input type="text" name="amount" class="form-control" id="amount"
                                        placeholder="Enter Amount" required
                                        data-parsley-error-message="Amount is required" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Date</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <input type="text" name="date" placeholder="Enter Date"
                                        class="form-control date_picker" required
                                        data-parsley-error-message="Date is required">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Payment Type</h6>
                                </div>
                                <div class="col-md-9">
                                    <select class="form-control select2" name="bank_id" id="paid_source" required
                                        data-parsley-error-message="Account is required">
                                        <option value="" selected>Select Payment Type
                                        </option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">
                                                @if ($bank->status == '0')
                                                    {{ $bank->name }}
                                                @else
                                                    {{ $bank->name }} - {{ $bank->branch_name }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>

                                    <div class="row" id="transaction_note" style="display: none;">
                                        <div class="col-12">
                                            <input type="text" placeholder="Note" class="form-control" name="note"
                                                id="note">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Charge(In Amount)</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" name="charge" placeholder="Enter Change Amount" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9 col-lg-9 text-secondary">
                                    <input type="submit" class="btn btn-info px-4" value="Add Expense" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script>
        $(document).ready(function() {

            $(document).on('change', '#expense_head', function() {
                let description = $(this).val();
                if (description == 'Other') {
                    $('.others').show();
                    $('.date').hide();
                } else if (description == 'Salary') {
                    $('.date').show();
                    $('.others').hide();
                } else {
                    $('.others').hide();
                    $('.date').hide();
                }
            });
        });
    </script>


    <!-- paid status -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#paid_source').on('change', function() {
                let paidSource = $(this).val();
                if (paidSource != '') {
                    $('#transaction_note').show();
                } else {
                    $('#transaction_note').hide();
                }
            });
        });
    </script>
@endsection
