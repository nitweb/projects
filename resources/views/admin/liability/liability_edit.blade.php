@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="text-muted mb-3">Edit Liabilities</h2>
                            <form class="custom-validation" action="{{ route('liability.update') }}" method="POST" novalidate="" autocomplete="off">
                                @csrf
                                <input type="hidden" name="id" id="id" value="{{ $LiabilityInfo->id }}">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="">Liabilities Name</label>
                                            <input type="text" id="head" name="head"
                                                value="{{ $LiabilityInfo->head }}" class="form-control" required=""
                                                placeholder="Liability Name"
                                                data-parsley-required-message="Liability Name is required">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <div>
                                                <label for="">Liabilities Source</label>
                                                <input type="text" value="{{ $LiabilityInfo->source }}" id="source" name="source" class="form-control"
                                                    required="" placeholder="Source"
                                                    data-parsley-required-message="Liability Source is required">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <div>
                                                <label for="">Liabilities Condition</label>
                                                <input type="text" value="{{ $LiabilityInfo->condition }}" id="condition" name="condition" class="form-control"
                                                    required="" placeholder="Condition"
                                                    data-parsley-required-message="Liability Condition is required">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <div>
                                                <label for="">Amount</label>
                                                <input type="text" value="{{ $LiabilityInfo->amount }}" id="amount" name="amount" class="form-control"
                                                    required="" placeholder="Amount"
                                                    step="0.01"
                                                    data-parsley-validation-threshold="1" data-parsley-trigger="keyup"
                                                    data-parsley-type="number" data-parsley-error-message="Please Enter Amount in number">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="mb-3">
                                            <div>
                                                <label for="">Date</label>
                                                <input type="text" id="date" name="date"
                                                    class="form-control date_picker" required=""
                                                    placeholder="Date" value="{{ $LiabilityInfo->date }}"
                                                    data-parsley-required-message="Date is required">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <div>
                                        <button type="submit" class="btn btn-info waves-effect waves-light me-1">
                                            Update Liability
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


    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
@endsection
