<!-- =============== VENDOR SCRIPTS ===============-->
<!-- MODERNIZR-->
<script src="{{URL::to('/')}}/vendor/modernizr/modernizr.custom.js"></script><!-- STORAGE API-->
<script src="{{URL::to('/')}}/vendor/js-storage/js.storage.js"></script><!-- SCREENFULL-->
<script src="{{URL::to('/')}}/vendor/screenfull/dist/screenfull.js"></script><!-- i18next-->
<script src="{{URL::to('/')}}/vendor/i18next/i18next.js"></script>
<script src="{{URL::to('/')}}/vendor/i18next-xhr-backend/i18nextXHRBackend.js"></script>
<script src="{{URL::to('/')}}/vendor/jquery/dist/jquery.js"></script>
<script src="{{URL::to('/')}}/vendor/popper.js/dist/umd/popper.js"></script>
<script src="{{URL::to('/')}}/vendor/bootstrap/dist/js/bootstrap.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
<!-- =============== PAGE VENDOR SCRIPTS ===============-->

<script>
    $(document).on('keydown',".onlyDigits",function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 188]) !== -1 ||
            // Allow: Ctrl+A,Ctrl+C,Ctrl+V, Command+A
            ((e.keyCode == 65 || e.keyCode == 86 || e.keyCode == 67) && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    $(document).on('keyup',".onlyDigits",function (e) {
        //изменение запятой на точку
        // this.value = this.value.replace(/,/g, '.');
        //тольуо числа
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9]/g, '');
        }
    });
//1 start 0 finish
    function changeStatus(type, startOrFinish){
        var data = {
            type:type,
            startorfinish:startOrFinish,
            _token: $('input[name=_token]').val(),
        };
        var url;
        if (type == 1){
            //start
            url = '{{ route('workstart') }}';
        } else {
            url = '{{ route('changestatus') }}'
        }
        $.ajax({
            url: url,
            method: 'post',
            data: data,
            success: function (response, status, xhr, $form) {
                if (response.error) {
                    toastr.error(response.error);
                } else {
                    location.href='{{ route('main') }}';
                }
            }
        });
    }

    //status 1 start 0 notworking/finish
    //type 0-сбор 1 проверка 2помощь
    function changeStatusOrder(order_id, type, order_status){
        var data = {
            order_id:order_id,
            type:type,
            status:order_status,
            _token: $('input[name=_token]').val(),
        };

        $.ajax({
            url: '{{ route('changestatusorder') }}',
            method: 'post',
            data: data,
            success: function (response, status, xhr, $form) {
                if (response.error) {
                    toastr.error(response.error);
                } else {
                    if (order_status == 1){
                        location.href='{{ route('action') }}';
                    } else {
                        location.href='{{ route('order.view','') }}/'+order_id;
                    }
                }
            }
        });
    }
    //доставка
    //status 1 start 0 notworking/finish
    function changeStatusDelivery(status){
        var data = {
            status:status,
            _token: $('input[name=_token]').val(),
        };

        $.ajax({
            url: '{{ route('changestatusdelivery') }}',
            method: 'post',
            data: data,
            success: function (response, status, xhr, $form) {
                if (response.error) {
                    toastr.error(response.error);
                } else {
                    if (data.status == 1){
                        location.href='{{ route('action') }}';
                    } else {
                        location.href='{{ route('main') }}';
                    }
                }
            }
        });
    }

    //ismanagertask
    //status 1 start 0 notworking/finish
    function changeStatusManagerTask(status){
        var data = {
            status:status,
            _token: $('input[name=_token]').val(),
        };

        $.ajax({
            url: '{{ route('changestatusmanagertask') }}',
            method: 'post',
            data: data,
            success: function (response, status, xhr, $form) {
                if (response.error) {
                    toastr.error(response.error);
                } else {
                    if (data.status == 1){
                        location.href='{{ route('action') }}';
                    } else {
                        location.href='{{ route('main') }}';
                    }
                }
            }
        });
    }
    //рабочей обязанности
    //status 1 start 0 notworking/finish
    function changeStatusDuty(duty_id,status){
        var data = {
            duty_id:duty_id,
            status:status,
            _token: $('input[name=_token]').val(),
        };

        $.ajax({
            url: '{{ route('changestatusduty') }}',
            method: 'post',
            data: data,
            success: function (response, status, xhr, $form) {
                if (response.error) {
                    toastr.error(response.error);
                } else {
                    if (data.status == 1){
                        location.href='{{ route('action') }}';
                    } else {
                        location.href='{{ route('main') }}';
                    }
                }
            }
        });
    }
    //старт пауза при сборке заказа
    //status 1 start 0 notworking/finish
    function changeStatusWorkPause(order_id,type,status){
        var data = {
            type:type,
            order_id:order_id,
            status:status,
            _token: $('input[name=_token]').val(),
        };

        $.ajax({
            url: '{{ route('changestatus.workpause') }}',
            method: 'post',
            data: data,
            success: function (response, status, xhr, $form) {
                if (response.error) {
                    toastr.error(response.error);
                } else {
                        location.reload();
                }
            }
        });
    }
    $(document).on('show.bs.modal', '.modal', function (event) {
        var zIndex = 2050 + (10 * $('.modal:visible').length);
        $(this).attr('style', 'z-index: ' + zIndex + '!important;');
        setTimeout(function () {
            $('.modal-backdrop').not('.modal-stack').attr('style', 'z-index: ' + (zIndex - 1) + '!important;').addClass('modal-stack');
        }, 0);
    });
</script>


