@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header py-3 bg-white">
                        <div class="row mb-4">
                            <div class="col-12 py-3 d-flex justify-content-center align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">Expense Report</h6>
                            </div>
                        </div>
                        <div class="row bg-white">
                            <form method="POST" action="{{ route('get.expense.report') }}" autocomplete="off">
                                @csrf
                                <div class="errorMsgContainer"></div>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control ml-2 date_picker" name="start_date" id="start_date" placeholder="Start Date" required>
                                    <input type="text" class="form-control ml-2 date_picker" name="end_date" id="end_date" placeholder="End Date" required>
                                    <button class="btn btn-primary submit_btn ml-2" type="submit">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="datatable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th width="5%">Sl</th>
                                        <th>Expense Head</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Expense Head</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($allExpense as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $item->head }}
                                            </td>
                                            <td>
                                                {{ $item->amount }}
                                            </td>
                                            <td>
                                                {{ $item->date }}
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
@endsection
