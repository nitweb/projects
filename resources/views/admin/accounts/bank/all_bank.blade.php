@extends('admin.admin_master')
@section('admin')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ $title }} List</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="{{ route('add.account') }}">
                    <button class="btn btn-info">Add {{ $title }}</button>
                </a>
            </h6>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-lg-12">

                <!-- Account Deposit Modal -->
                <div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="depositModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="depositModalLabel">Deposit Amount</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form class="custom-validation" action="{{ route('deposit.amount') }}" method="POST"
                                    novalidate="" id="depositForm" autocomplete="off">
                                    @csrf
                                    <div class="errorMsgContainer"></div>
                                    <input type="hidden" id="account_id" name="account_id">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <input type="digit" id="current_amount" name="current_amount"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <input type="digit" id="amount" name="amount" class="form-control"
                                                    required="" data-parsley-trigger="keyup"
                                                    data-parsley-validation-threshold="0" min="1" placeholder="Add Deposit Amount"
                                                    data-parsley-type="number"
                                                    data-parsley-type-message="Input must be positive number"
                                                    data-parsley-required-message="Deposit Amount is required">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <input type="text" id="deposit_note" name="deposit_note"
                                                    placeholder="Enter Deposit Note" class="form-control" required=""
                                                    data-parsley-required-message="Deposit Note is required">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <input type="text" id="date" name="date" placeholder="Enter Date"
                                                    class="form-control date_picker" required=""
                                                    data-parsley-required-message="Date is required">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <div>
                                            <button type="submit"
                                                class="btn btn-info waves-effect waves-light me-1 add-deposit">
                                                Save Changes
                                            </button>
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Account Info</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sl</th>
                                    <th>Account Info</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($allBank as $key => $bank)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <div class="account-info">
                                                @if ($bank->status == '1')
                                                    <p class="mb-0">{{ $bank->name }}</p>
                                                @endif
                                                <p class="mb-0">{{ $bank->account_number }}</p>
                                                <p class="mb-0">{{ $bank->branch_name }}</p>
                                                <p class="mb-0">{{ $bank->phone_number }}</p>
                                            </div>
                                        </td>
                                        <td class="align-items-center">
                                            <p class="mb-0 fw-bold">{{ number_format($bank->balance) }}/-</p>
                                        </td>
                                        <td>
                                            <div class="align-items-center justify-content-start">
                                                <a title="Add Payment" style="margin-left: 5px;"
                                                    data-id="{{ $bank->id }}" data-balance="{{ $bank->balance }}"
                                                    href="{{ route('deposit.amount')}}"
                                                    class="btn btn-info depositBtn" data-bs-toggle="modal"
                                                    data-bs-target="#depositModal">
                                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                                </a>
                                                @if ($bank->status == '0')
                                                    Default
                                                @else
                                                    <a title="Edit Bank" style="margin-left: 5px;"
                                                        href="{{ route('edit.account', $bank->id) }}"
                                                        class="btn btn-info">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a title="Delete Bank" id="delete" style="margin-left: 5px;"
                                                        href="{{ route('delete.account', $bank->id) }}"
                                                        class="btn btn-danger" title="Delete Bank">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.accounts.bank.bank_deposit')
@endsection
