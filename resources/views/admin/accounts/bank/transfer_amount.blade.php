@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-10 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-muted">{{ $title }}</h4>
                            <form class="custom-validation" action="{{ route('transfer.amount.store') }}" method="POST"
                                novalidate="" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <select name="from_bank_id" id="from_bank_id" class="form-control select2"
                                                required="" data-parsley-required-message="Bank Account is required">
                                                <option value="">Account From</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <div class="mb-3">
                                                <select name="to_bank_id" id="to_bank_id" class="form-control select2"
                                                    required="" data-parsley-required-message="Bank Account is required">
                                                    <option value="">Account To</option>
                                                    @foreach ($accounts as $account)
                                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <input type="text" name="description" class="form-control"
                                                placeholder="Enter description" required
                                                data-parsley-required-message="Description is required">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <input type="text" id="amount" name="amount"
                                                class="form-control @error('amount') is-invalid @enderror" required=""
                                                placeholder="Enter Amount"
                                                data-parsley-required-message="Amount is required">
                                            @error('amount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <input type="text" name="date" id="date"
                                                class="form-control date_picker" placeholder="Enter Date" required
                                                data-parsley-required-message="Date is required">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <div>
                                        <button type="submit" class="btn btn-info waves-effect waves-light me-1">
                                            Add Account
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
    <script>
        $(document).ready(function() {
            // image on load
            $('#image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>
@endsection
