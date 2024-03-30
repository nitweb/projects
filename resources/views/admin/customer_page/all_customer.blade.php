@extends('admin.admin_master')
@section('admin')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ $title }}</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="{{ route('add.customer') }}">
                    <button class="btn btn-info">Add Customer</button>
                </a>
            </h6>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Company ID</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Due Amount</th>
                                    <th>Replacement</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Company ID</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Due Amount</th>
                                    <th>Replacement</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($allData as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            {{ $item->name }}
                                        </td>
                                        <td>
                                            {{ $item->customer_id }}
                                        </td>
                                        <td>
                                            {{ $item->email }}
                                        </td>
                                        <td>
                                            {{ $item->phone }}
                                        </td>
                                        <td>
                                            {{ $item->address }}
                                        </td>
                                        <td>
                                            @php
                                                $due_amount = App\Models\Payment::where('customer_id', $item->id)->sum(
                                                    'due_amount',
                                                );
                                            @endphp
                                            BDT {{ number_format($due_amount) }}
                                        </td>

                                        <td>
                                            @php
                                                $replacement = App\Models\CustomerProductReplaceHistory::where(
                                                    'customer_id',
                                                    $item->id,
                                                )->sum('quantity');
                                            @endphp
                                            {{ $replacement }}
                                        </td>


                                        <td>
                                            <a style="margin-left: 5px;" href="{{ route('edit.customer', $item->id) }}"
                                                class="btn btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a style="margin-left: 5px;" href="{{ route('customer.bill', $item->id) }}"
                                                class="btn btn-dark" title="Customer Bill">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                View Bill
                                            </a>
                                            {{-- <a id="delete" title="Delete Company" style="margin-left: 5px;"
                                                href="{{ route('delete.company', $item->id) }}" class="btn btn-danger">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                            <a id="delete" style="margin-left: 5px;"
                                                href="{{ route('company.bill.delete', $item->id) }}" class="btn btn-danger"
                                                title="Company Bill">
                                                <i class="fas fa-trash-alt    "></i>
                                                Delete All Bill
                                            </a> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('backend/assets/js/code.js') }}"></script>
    @endsection
