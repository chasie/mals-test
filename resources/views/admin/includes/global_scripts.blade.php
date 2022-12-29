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
    Object.size = function(obj) {
        var size = 0,
            key;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) size++;
        }
        return size;
    };
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
</script>


