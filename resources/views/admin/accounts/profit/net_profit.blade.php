@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="row py-5">
            <div class="col-12">
                <form method="POST" action="{{ route('get.net.sales.profit') }}">
                    @csrf
                    <div class="errorMsgContainer"></div>
                    <div class="input-group mb-3">
                        <input type="date" class="form-control ml-2 date_picker" name="start_date" id="start_date"
                            value="{{ Request::post('start_date') }}">
                        <input type="date" class="form-control ml-2 date_picker" name="end_date" id="end_date"
                            value="{{ Request::post('end_date') }}">
                        <button class="btn btn-info submit_btn ml-2 netProfitBtn" type="submit">Search</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-muted text-center">
                            Net Profit from
                            @if (Request::post() == true)
                                {{ date('d-m-Y', strtotime(Request::post('start_date'))) }} to
                                {{ date('d-m-Y', strtotime(Request::post('end_date'))) }}
                            @else
                                Opening Date
                            @endif
                        </h4>
                        <h5 class="text-center text-muted mb-3">Total Profit:
                            {{-- <strong>
                                @if (str_starts_With($profit, '-') == true)
                                    ({{ number_format(abs($profit)) }})
                                @else
                                    BDT {{ number_format($profit) }}
                                @endif --}}
                                {{$salesProfit}}
                            </strong>
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Head</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Sales Order</td>
                                        <td>{{ $sellingAmount }}</td>
                                    </tr>
                                    <tr>
                                        <td>Sales Discount</td>
                                        <td>{{ $sellingDiscount }}</td>
                                    </tr>
                                    <tr>
                                        <td>Purchase Amount</td>
                                        <td>({{ $purchaseAmount }})</td>
                                    </tr>
                                    <tr>
                                        <td>Expesne Amount</td>
                                        <td>({{ $expense }})</td>
                                    </tr>
                                    <tr>
                                        <td>Sales Amount</td>
                                        <td>{{ $sellingAmount }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Amount</td>
                                        <td>{{ $sales_amount - $purchaseAmount - $expense}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            // show edit value in update form
            $(document).on('click', '.netProfitBtn', function(e) {
                let id = $(this).data('id');
                let start_date = $('#start_date').val();
                let end_date = $('#end_date').val();
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                    url: "{{ route('get.net.sales.profit') }}",
                    method: 'post',
                    data: {
                        start_date: start_date,
                        end_date: end_date,
                    },
                },
                success: function(res) {
                    console.log(res);
                },

            );

            // add hour js
            // $(document).on('click', '.liability-payment', function(e) {
            //     e.preventDefault();
            // })
        });
    </script>
@endsection
