@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Liability</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="{{ route('liability.add') }}">
                    <button class="btn btn-info"> <i class="fa fa-plus-circle" aria-hidden="true"> Add Liability </i></button>
                </a>
            </h6>
        </div>
        <!--end breadcrumb-->

        <!-- Button trigger modal -->
        {{-- <button type="button" class="btn btn-primary" >
    Launch demo modal
  </button> --}}

        <!-- Bonus Modal -->
        <div class="modal fade" id="LiabilityPayModal" tabindex="-1" aria-labelledby="LiabilityPayLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="AddHourModalLabel">Liabilities Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="custom-validation" action="{{ route('liability.payment') }}" method="POST"
                            novalidate="" id="LiabilityPaymentForm" autocomplete="off">
                            @csrf
                            <div class="errorMsgContainer"></div>
                            <input type="hidden" id="liability_id" name="liability_id">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="digit" id="amount" name="amount" class="form-control"
                                            required="" data-parsley-trigger="keyup"
                                            data-parsley-validation-threshold="0" placeholder="Liability Payment Amount"
                                            data-parsley-type="number"
                                            data-parsley-type-message="Input must be positive number"
                                            data-parsley-required-message="Liability Payment is required">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <select class="form-control" name="bank_id" id="bank_id">
                                            <option value="">Select Payment Account</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="date" id="date" name="date" class="form-control date_picker"
                                            required="" data-parsley-required-message="Effected Date is required">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-0">
                                <div>
                                    <button type="submit"
                                        class="btn btn-info waves-effect waves-light me-1 liability-payment">
                                        Submit
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Update</button>
          </div> --}}
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="datatable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th width="5%">Sl</th>
                                        <th>Liability Head</th>
                                        <th>Source</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th width="20%">Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Liability Head</th>
                                        <th>Source</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($LiabilityAll as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $item->head }}
                                            </td>
                                            <td>
                                                {{ $item->source }}
                                            </td>
                                            
                                            <td>
                                                {{ number_format($item->amount) }}
                                            </td>
                                            
                                            <td>
                                                {{ $item->date }}
                                            </td>

                                            <td>
                                                <a style="margin-left: 5px;" href="{{ route('liability.edit', $item->id) }}"
                                                    class="btn btn-info">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a id="delete" style="margin-left: 5px;"
                                                    href="{{ route('liability.delete', $item->id) }}"
                                                    class="btn btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                                @if ($item->amount != 0)
                                                    <a style="margin-left: 5px;" data-id="{{ $item->id }}"
                                                        class="btn btn-success liabilityBtn" data-bs-toggle="modal"
                                                        data-bs-target="#LiabilityPayModal">
                                                        Pay Now
                                                    </a>
                                                @endif
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
    </div>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('backend/assets/js/code.js') }}"></script>
    @include('admin.liability.liability_pay_js')

    <!-- Modal -->
    {{-- <div class="modal fade" id="liabilityModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Liability Payment</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="#" autocomplete="off">
                @csrf
                <div class="errorMsgContainer"></div>
                <div class="input-group mb-3">
                    <input type="number" class="form-control ml-2" name="amount" id="amount" placeholder="Amount" required>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-info">Submit</button>
        </div>
      </div>
    </div>
  </div> --}}
@endsection
