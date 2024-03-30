@extends('admin.admin_master')
@section('admin')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ $title }} List</h6>
            <h6 class="m-0 font-weight-bold text-primary">
            </h6>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Account</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sl</th>
                                    <th>Account</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($transactionList as $key => $transaction)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            {{ $transaction->bank->name }}
                                        </td>
                                        <td>
                                            @if ($transaction->trans_type == 'earning' || $transaction->trans_type == 'asset')
                                                <span class="badge bg-success text-capitalize">{{ $transaction->trans_type }}</span>
                                            @elseif($transaction->trans_type == 'transfer')
                                                <span class="badge bg-warning text-capitalize">{{ $transaction->trans_type }}</span>
                                            @else
                                                <span class="badge bg-danger text-capitalize">{{ $transaction->trans_type }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $transaction->trans_head }}</td>
                                        <td>{{ number_format($transaction->balance) }}</td>
                                        <td>{{ $transaction->date }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('backend/assets/js/code.js') }}"></script>
@endsection
