<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="Bootstrap Admin App" />
    <meta name="keywords" content="app, responsive, jquery, bootstrap, dashboard, admin" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />

    <title>Login</title>

    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/@fortawesome/fontawesome-free/css/brands.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/@fortawesome/fontawesome-free/css/regular.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/@fortawesome/fontawesome-free/css/solid.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/@fortawesome/fontawesome-free/css/fontawesome.css"><!-- SIMPLE LINE ICONS-->
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/simple-line-icons/css/simple-line-icons.css"><!-- =============== BOOTSTRAP STYLES ===============-->
    <link rel="stylesheet" href="{{URL::to('/')}}/css/bootstrap.css" id="bscss"><!-- =============== APP STYLES ===============-->
    <link rel="stylesheet" href="{{URL::to('/')}}/css/app.css" id="maincss">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" id="maincss">
</head>

<body>
{{csrf_field()}}
<div class="wrapper">
    <div class="block-center mt-4 wd-xl">
        <!-- START card-->
        <div class="card card-flat">
            <div class="card-header text-center bg-light text-uppercase h1 p-1" style="font-weight: 800; color: #e6e6e6; letter-spacing: -6px; font-size: 80px;" >Mals</div>
{{--            <div class="card-header text-center bg-light pt-4 pb-4"><a href="#"><img class="block-center rounded" src="{{URL::to('/')}}/img/custom/logo.svg" alt="Image"></a></div>--}}

            <div class="card-body">
                <p class="text-center py-2">Авторизация</p>
                <form class="mb-3" id="loginForm" >
                    <div class="form-group">
                        <div class="input-group with-focus">
                            <input class="form-control border-right-0" id="email" name="email" type="email" placeholder="E-mail" required>
                            <div class="input-group-append"><span class="input-group-text text-muted bg-transparent border-left-0"><em class="fa fa-envelope"></em></span></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group with-focus">
                            <input class="form-control border-right-0" id="password" name="password" type="password" placeholder="Пароль" required>
                            <div class="input-group-append"><span class="input-group-text text-muted bg-transparent border-left-0"><em class="fa fa-lock"></em></span></div>
                        </div>
                    </div>
                    <div class="clearfix">
{{--                        <div class="checkbox c-checkbox float-left mt-0"><label><input type="checkbox" value="" name="remember"><span class="fa fa-check"></span> Remember Me</label></div>--}}
{{--                        <div class="float-right"><a class="text-muted" href="recover.html">Forgot your password?</a></div>--}}
                    </div><button class="btn btn-block btn-primary mt-3" type="submit" id="save">Вход</button>
                </form>
{{--                <p class="pt-3 text-center">Need to Signup?</p><a class="btn btn-block btn-secondary" href="register.html">Register Now</a>--}}
            </div>
        </div><!-- END card-->
        <div class="p-3 text-center"><span class="mr-2">&copy;</span><span>{{\Carbon\Carbon::now()->format('Y')}}</span><span> Mals</span><br><span></span></div>
    </div>
</div><!-- =============== VENDOR SCRIPTS ===============-->
<script src="{{URL::to('/')}}/vendor/modernizr/modernizr.custom.js"></script><!-- STORAGE API-->
<script src="{{URL::to('/')}}/vendor/js-storage/js.storage.js"></script><!-- i18next-->
<script src="{{URL::to('/')}}/vendor/i18next/i18next.js"></script>
<script src="{{URL::to('/')}}/vendor/i18next-xhr-backend/i18nextXHRBackend.js"></script><!-- JQUERY-->
<script src="{{URL::to('/')}}/vendor/jquery/dist/jquery.js"></script><!-- BOOTSTRAP-->
<script src="{{URL::to('/')}}/vendor/popper.js/dist/umd/popper.js"></script>
<script src="{{URL::to('/')}}/vendor/bootstrap/dist/js/bootstrap.js"></script><!-- PARSLEY-->
<script src="{{URL::to('/')}}/vendor/parsleyjs/dist/parsley.js"></script>
<script src="{{URL::to('/')}}/vendor/jquery-validation/dist/jquery.validate.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
<!-- =============== APP SCRIPTS ===============-->
{{--<script src="{{URL::to('/')}}/js/app.js"></script>--}}
<script>
    $('#save').on('click', function (e) {
        e.preventDefault();
        var form = $(this).closest('form');
        // <div class="text-help filled" id="parsley-id-5" aria-hidden="false"><div class="parsley-required">This value is required.</div></div>
        form.validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true
                }
            },
            messages:{
                email: {
                    required: 'Это поле обязательно',
                    email: 'Введите корректный E-mail'
                },
                password: {
                    required: 'Это поле обязательно'
                }
            },
            errorPlacement: function (t, e) {
                var parent;
                parent = e.closest('.form-group');
                parent.append(t.addClass('parsley-required'));
            },
            highlight: function (t) {
                $(t).addClass('is-invalid');
            },
            unhighlight: function (t) {
                $(t).removeClass('is-invalid');
            },
        });

        if (!form.valid()) {
            return;
        }
        var data = {
            email: $('#email').val(),
            password: $('#password').val(),
            _token: $('input[name=_token]').val(),
        };


        $.ajax({
            url: '{{URL::to('/')}}' + '/admin/login',
            method: 'post',
            data: data,
            success: function (response, status, xhr, $form) {
                if (response.error) {
                        toastr.error(response.error);
                } else {
                    location.href = '{{URL::to('/')}}/admin';
                }
            },
            error: function (error){
                toastr.error('server error');
            }
        });
    });
</script>
</body>

</html>
