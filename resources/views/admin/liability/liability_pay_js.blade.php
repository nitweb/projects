<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script>
    $(document).ready(function() {

        // show edit value in update form
        $(document).on('click', '.liabilityBtn', function() {
            let id = $(this).data('id');
            $('#liability_id').val(id);
            console.log('id', id);
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        // add hour js
        $(document).on('click', '.liability-payment', function(e) {
            e.preventDefault();
            let amount = $("#amount").val();
            let date = $("#date").val();
            let liability_id = $("#liability_id").val();
            let bank_id = $("#bank_id").val();
            // alert(bank_id);
            console.log(amount, date, liability_id);
            $.ajax({
                url: "{{ route('liability.payment') }}",
                method: 'post',
                data: {
                    liability_id: liability_id,
                    bank_id: bank_id,
                    amount: amount,
                    date: date,
                },
                success: function(res) {
                    if (res.status == 'success') {
                        $("#LiabilityPayModal").modal('hide');
                        $("#LiabilityPaymentForm")[0].reset();
                        $(".errorMsgContainer").html("");
                        $(".table").load(location.href + ' .card');


                        Command: toastr["success"](
                            "Liability Paid Successfully!"
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
                        $("#LiabilityPayModal").modal('hide');
                        $("#LiabilityPaymentForm")[0].reset();
                        $(".errorMsgContainer").html("");
                        $(".table").load(location.href + ' .card');


                        if(res.liability == 'error-liability'){
                            Command: toastr["error"](
                            "Liablility Amount is less than request amount!"
                        )
                        } else{
                            Command: toastr["error"](
                            "Insufficient Balance on Selected Bank!"
                        )
                        }


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

        function refreshPage() {
            location.reload(true);
        }
    });
</script>
