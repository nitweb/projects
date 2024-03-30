@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('store.commission') }}" method="POST" class="custom-validation"
                            autocomplete="off" novalidate="">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mt-5">
                                    <table class="table border table-responsive table-striped">
                                        <thead class="bg-body">
                                            <tr>
                                                <th width="20%" class="text-center">Category</th>
                                                <th class="text-center">Target Amount</th>
                                                <th class="text-center">Commission</th>
                                                <th class="text-center">
                                                    <button class="btn btn-success" type="button" onclick="cloneRow()">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody">
                                            <tr class="tr">
                                                <td class="text-center">
                                                    <select name="category_id[]" id="category_1"
                                                        class="form-control form-select select2" required=""
                                                        data-parsley-required-message="Category Id is required">
                                                        <option selected value="">Select Category</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control sales_target"
                                                        placeholder="Sales target" name="sales_target[]" id="sales_target"
                                                        required=""
                                                        data-parsley-required-message="Sales target Id is required">
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control sales_commission"
                                                        placeholder="Sales commission" name="sales_commission[]"
                                                        id="sales_commission" required=""
                                                        data-parsley-required-message="Sales commission Id is required">
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" onclick="removeRow(event)"
                                                        class="btn btn-danger">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="row mb-3 mt-5">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <select name="employee_id" id="employee_id"
                                                    class="form-control form-select select2" required=""
                                                    data-parsley-required-message="Employee id is required">
                                                    <option selected value="">Select Employee Type</option>
                                                    @foreach ($allEmployee as $employee)
                                                        <option value="{{ $employee->id }}">{{ $employee->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-info" id="storeButton">Add
                                                    Commission</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- js --}}
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <script>
        let count = 2;

        function cloneRow() {
            const tr = `
            <tr class="tr">
                <td class="text-center">
                    <select name="category_id[]" id="category_${count}"
                        class="form-control form-select select2" required=""
                        data-parsley-required-message="Category Id is required">
                        <option selected value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="text-center">
                    <input type="text" class="form-control sales_target"
                        placeholder="Sales target" name="sales_target[]" id="sales_target"
                        required=""
                        data-parsley-required-message="Sales Targer is required">
                </td>
                <td class="text-center">
                    <input type="text" class="form-control sales_commission"
                        placeholder="Sales Commission" name="sales_commission[]" id="sales_commission"
                        required=""
                        data-parsley-required-message="Sales Commission is required">
                </td>
                <td class="text-center">
                    <button type="button" onclick="removeRow(event)"
                        class="btn btn-danger">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>

            `;
            $('.tbody').append(tr);
            count++;
        }

        function removeRow(event) {
            if ($('.tr').length > 1) {
                $(event.target).closest('.tr').remove();
                totalAmountOfPrice();
            }
        }
    </script>

    <!-- paid status -->
@endsection
