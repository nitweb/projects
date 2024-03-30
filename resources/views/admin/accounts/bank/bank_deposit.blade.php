<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script>
    $(document).ready(function() {

        // show edit value in update form
        $(document).on('click', '.depositBtn', function() {
            let id = $(this).data('id');
            let balance = $(this).data('balance');
            $('#account_id').val(id);
            $('#current_amount').val(balance);
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        // add deposit
        $(document).on('click', '.add-deposit', function(e) {
            e.preventDefault();
            let previous_amount = $("#current_amount").val();
            let date = $("#date").val();
            let account_id = $("#account_id").val();
            let deposit_amount = $("#amount").val();
            let deposit_note = $("#deposit_note").val();

            $.ajax({
                url: "{{ route('deposit.amount') }}",
                method: 'post',
                data: {
                    account_id: account_id,
                    account_id: account_id,
                    previous_amount: previous_amount,
                    deposit_amount: deposit_amount,
                    date: date,
                },
                success: function(res) {
                    if (res.status == 'success') {
                        $("#depositModal").modal('hide');
                        $("#depositForm")[0].reset();
                        $(".errorMsgContainer").html("");
                        $(".table").load(location.href + ' .card');


                        Command: toastr["info"](
                            "Deposit Added Successfully!"
                        )

                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                    }
                    if (res.status == 'error') {
                        $("#depositModal").modal('hide');
                        $("#depositForm")[0].reset();
                        $(".errorMsgContainer").html("");
                        $(".table").load(location.href + ' .card');


                        Command: toastr["error"](
                            "Deposit and date field is required!"
                        )


                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                    }
                },
                error: function(err) {
                    console.log(err);
                    let error = err.responseJSON;
                    console.log(error);
                    $.each(error.errors, function(index, value) {
                        $(".errorMsgContainer").append(
                            '<span class="text-danger">' + value + '</span>' +
                            '<br>');
                    });
                }
            });
        });

        // function refreshPage() {
        //     location.reload(true);
        // }
    });
</script>
