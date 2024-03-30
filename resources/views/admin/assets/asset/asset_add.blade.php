@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="text-muted mb-3">Add Asset</h2>
                            <form class="custom-validation" action="{{ route('asset.store') }}" method="POST" novalidate="" autocomplete="off">
                                @csrf
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <input type="text" id="name" name="name" class="form-control"
                                                required="" placeholder="Asset Name"
                                                data-parsley-required-message="Asset Name is required">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <div>
                                                <select name="cat_id" id="cat_id" class="form-control" data-parsley-required-message="Asset Category is required" required="">
                                                    <option selected disabled>Select Category</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <div>
                                                <select name="sub_cat_id" id="sub_cat_id" class="form-control">
                                                    <option selected disabled>Select Sub Category</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <div>
                                                <input type="text" id="price" name="price" class="form-control"
                                                    required="" placeholder="Price"
                                                    data-parsley-required-message="Asset Price is required">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="mb-3">
                                            <div>
                                                <select name="type" id="type" class="form-control" data-parsley-required-message="Asset Type is required" required>
                                                    <option selected disabled>Select Type</option>
                                                    <option value="current">Current</option>
                                                    <option value="fixed">Fixed</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <div>
                                                <select name="status" id="statuss" class="form-control" data-parsley-required-message="Depreciation Status is required" required>
                                                    <option selected disabled>Depreciation Status</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6" id="longivity">
                                        <div class="mb-3">
                                            <div>
                                                {{-- <input type="text" id="longevity" name="longevity" class="form-control"
                                                required="" data-parsley-type="digits placeholder="Longevity"
                                                data-parsley-required-message="Longevity is required"> --}}
                                                <input type="text" name="longevity" id="longevity" class="form-control" placeholder="Enter Longivity in month" step="1"
                                                    data-parsley-validation-threshold="1" data-parsley-trigger="keyup"
                                                    data-parsley-type="digits" data-parsley-error-message="Please Enter Number of Month" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <div>
                                                <input type="text" id="purchase_date" name="purchase_date"
                                                    class="form-control date_picker" required=""
                                                    placeholder="Purchase Date"
                                                    data-parsley-required-message="Purchase Date is required">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <div>
                                        <button type="submit" class="btn btn-info waves-effect waves-light me-1">
                                            Add Asset
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $('#cat_id').on('change', function() {
                let categoryId = $(this).val();
                // console.log(categoryId);
                $.ajax({
                    url: '{{ route('asset.get.sub.category') }}?cat_id=' + categoryId,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        cat_id: categoryId
                    },
                    success: function(data) {

                        let html = '<option value=""> Select Sub Category </option>';
                        $.each(data, function(key, value) {
                            html += '<option value=" ' + value.id + ' "> ' +
                                value.name + '</option>';
                        });
                        $("#sub_cat_id").html(html);
                    }
                });
            });
        });
    </script>

    <script>
        $('#longivity').hide();
        $('#statuss').on('change', function() {
            let status = $(this).val();

            if(status == 1){
              $('#longivity').show();
              $('#longevity').prop('required',true);
            } else{
                $('#longivity').hide();
                $('#longevity').prop('required',false);
            }
        });
    </script>
@endsection
