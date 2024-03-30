@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Asset</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="{{ route('asset.add') }}">
                    <button class="btn btn-info"> <i class="fa fa-plus-circle" aria-hidden="true"> Add Asset </i></button>
                </a>
            </h6>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="datatable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th width="5%">Sl</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Purchase Date</th>
                                        <th>Longevity</th>
                                        <th width="20%">Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Purchase Date</th>
                                        <th>Longevity</th>
                                        <th>Action</th>
                                    </tr>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($AssetAll as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $item->name }}
                                            </td>
                                            <td>
                                                {{ $item->price }}
                                            </td>
                                            <td>
                                                {{ $item->purchase_date }}
                                            </td>
                                            <td>
                                                {{ $item->longevity }}
                                            </td>

                                            <td>
                                                <a style="margin-left: 5px;"
                                                    href="{{ route('asset.edit', $item->id) }}"
                                                    class="btn btn-info">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a id="delete" style="margin-left: 5px;"
                                                    href="{{ route('asset.delete', $item->id) }}"
                                                    class="btn btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
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
