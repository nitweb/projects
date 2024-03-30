@extends('admin.admin_master')
@section('admin')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!-- Begin Page Content -->
    <div class="page-content">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-12 py-3 d-flex justify-content-center align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary text-center">Category Wise Daily Sales Report</h6>
                        <h6 class="m-0 font-weight-bold text-primary">
                            {{-- <a href="{{ route('add.opening.balance') }}">
                                <button class="btn btn-info">Add Balance</button>
                            </a> --}}
                        </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('get.cat.report') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" placeholder="Start Date" class="form-control ml-2 date_picker"
                            name="start_date" id="start_date" required>
                        <input type="text" placeholder="End Date" class="form-control ml-2 date_picker" name="end_date"
                            id="end_date" required>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="" selected disabled>Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select name="report_type" id="report_type" class="form-control" required>
                            <option value="" selected disabled>Select Report Head</option>
                            <option value="purchase">Purchase</option>
                            <option value="sales">Sales</option>
                        </select>
                        <button class="btn btn-primary submit_btn ml-2" type="submit">Search</button>
                    </div>
                </form>
            </div>

            {{-- <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr class="text-center">
                                <th>Date</th>
                                <th>Bill No</th>
                                <th>Category</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="text-center">
                                <th>Date</th>
                                <th>Bill No</th>
                                <th>Category</th>
                                <th>Amount</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @php
                                $cat_info = App\Models\InvoiceDetail::get();
                            @endphp
                            @foreach ($cat_info as $key => $info)
                                <tr class="text-center">
                                    <td>{{ date('d M Y', strtotime($info->date)) }}</td>
                                    <td>
                                        @if ($info->invoice_id != null)
                                            <a target="_blank" href="{{ route('invoice.view', $info->invoice_id) }}">
                                                {{ $info->invoice->invoice_no }}</a>
                                        @else
                                        @endif
                                    </td>
                                    <td> {{ $info['category']['name'] }} </td>
                                    <td> {{ $info->selling_price }} </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div> --}}

        </div>

    </div>

    <!-- End Page Content -->
@endsection
