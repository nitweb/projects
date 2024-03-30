@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body py-5">
                        <form action="{{ route('update.opening.balance') }}" method="POST" class="custom-validation"
                            novalidate="" autocomplete="off">
                            @csrf
                            <div class="row">
                                <input type="hidden" value="{{ $bankInfo->id }}" name="id">
                                <div class="col-12">
                                    <h4 class="m-0 font-weight-bold text-secondary">Update Bank Opening Balance</h4>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="mb-2">
                                        <select name="bank_id" id="bank_id" class="form-control" required
                                            data-parsley-required-message="Bank is required" autocomplete="off">
                                            <option disabled selected>Select Bank</option>
                                            @foreach ($banks as $bank)
                                                <option value="{{ $bank->id }}"
                                                    {{ $bank->id == $bankInfo->bank_id ? 'selected' : '' }}>
                                                    {{ $bank->bank_name }} -
                                                    {{ $bank->branch_name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <div class="mb-2">
                                        <input type="digit" id="total_amount" name="total_amount" class="form-control"
                                            required="" data-parsley-trigger="keyup"
                                            data-parsley-validation-threshold="0" placeholder="Total Amount"
                                            data-parsley-type="number"
                                            data-parsley-type-message="Input must be positive number"
                                            data-parsley-required-message="Total Amount is required" autocomplete="off"
                                            value="{{ $bankInfo->amount }}">
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <div class="mb-3">
                                        <input type="text" autocomplete="off" id="date" name="date"
                                            class="form-control date_picker" required value="{{$bankInfo->date}}"
                                            data-parsley-required-message="Date is required" placeholder="Enter Your Date">
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <button type="submit" class="btn btn-info waves-effect waves-light me-1">
                                        Update Balance
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
