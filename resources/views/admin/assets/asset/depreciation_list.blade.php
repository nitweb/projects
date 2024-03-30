@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header py-3 bg-white">
                        <div class="row mb-4">
                            <div class="col-12 py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">Current Month Depreciation</h6>
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <a href="{{ route('asset.add') }}">
                                        <button class="btn btn-info">Add Asset</button>
                                    </a>
                                </h6>
                            </div>
                        </div>
                        <div class="row">
                            <form method="POST" action="{{ route('get.depreciation') }}" autocomplete="off">
                                @csrf
                                <div class="errorMsgContainer"></div>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control ml-2 date_picker" name="start_date"
                                        id="start_date" placeholder="Start Date" required>
                                    <input type="text" class="form-control ml-2 date_picker" name="end_date"
                                        id="end_date" placeholder="End Date" required>
                                    <button class="btn btn-primary submit_btn ml-2" type="submit">Search</button>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="card-body">
                        <h5 class="text-center text-muted mb-3">Total Depreciation:
                            <strong>{{ $depreciation->sum('amount') }} TK.</strong>
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="datatable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th width="5%">Sl</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($depreciation as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <span class="text-capitalize">{{ $item->name }}</span>
                                            </td>
                                            <td>
                                                {{ number_format($item->amount) }}
                                            </td>
                                            <td>
                                                {{ date('F, Y', strtotime($item->date)) }}
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
@endsection
