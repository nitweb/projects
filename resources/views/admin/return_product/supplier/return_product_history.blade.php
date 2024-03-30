@extends('admin.admin_master')
@section('admin')
    <!-- Begin Page Content -->
    <div class="page-content">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-12 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="m-0 font-weight-bold text-primary">{{ $supplier->name }}</h6>
                                <p class="m-0">{{ $supplier->email }}</p>
                                <p class="m-0">{{ $supplier->mobile_no }}</p>
                                <p class="m-0 ">Supplier Product Replacement History</p>
                            </div>
                            <h6 class="m-0 font-weight-bold text-primary">
                                <a href="{{ url()->previous() }}">
                                    <button class="btn btn-dark"> <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                        Back</button>
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Product Name</th>
                                <th>Customer Name</th>
                                <th>Quantity</th>
                                <th>Return Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Sl</th>
                                <th>Product Name</th>
                                <th>Customer Name</th>
                                <th>Quantity</th>
                                <th>Return Date</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($returnHistory as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td class="text-capitalize">
                                        {{ $item->product->name }}
                                    </td>
                                    <td class="text-capitalize">
                                        {{ $item->customer->name }}
                                        {{ $item->replace_id }}
                                    </td>
                                    <td>
                                        {{ $item->quantity }} {{ $item->product->unit->short_form }}
                                    </td>
                                    <td class="text-capitalize">
                                        {{ date('d F, Y', strtotime($item->date)) }}
                                    </td>
                                    <td>
                                        @if ($item->status == '0')
                                            <a href="{{ route('store.replace.granted', $item->id) }}" class="btn btn-dark"
                                                id="sendCustomer" title="Send To Customer">
                                                <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                                Send To Customer
                                            </a>

                                            {{-- <form class="d-inline" action="{{ route('store.replace.granted', $item->id) }}"
                                                method="GET">
                                                @csrf
                                                <input type="hidden" name="replace_id" value="{{ $item->replace_id }}">
                                                <input type="hidden" name="supplier_replace_id"
                                                    value="{{ $item->id }}">
                                                <button class="btn btn-dark" id="sendCustomer"><i class="fa fa-paper-plane"
                                                        aria-hidden="true"></i>
                                                    Send To Customer</button> --}}
                                            </form>
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

    <!-- End Page Content -->
@endsection
