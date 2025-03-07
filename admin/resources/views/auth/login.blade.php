<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/square/blue.css') }}">

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page" style="background: linear-gradient(rgba(0,0,0,1), rgba(0, 35, 70, 1));">

    <div class="login-box">
        <div class="login-logo">
        <a href="#"><img src="{{ asset('img/Logo_blanco.png') }}" alt="logo"></a>
        </div>

        <div class="login-box-body">
            <p class="login-box-msg">Iniciar Sessíon</p>

            <form action="{{ route('login') }} " method="POST">
                @csrf

                <div class="form-group has-feedback">
                    <input id="USUARI_Correo___b" type="email" class="form-control{{ $errors->has('USUARI_Correo___b') ? ' is-invalid' : '' }}" name="USUARI_Correo___b" placeholder="Correo Electronico" value="{{ old('USUARI_Correo___b') }}" required autofocus>
                    @if ($errors->has('USUARI_Correo___b'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('USUARI_Correo___b') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group has-feedback">
                    <input id="USUARI_Clave_____b" type="password" class="form-control{{ $errors->has('USUARI_Clave_____b') ? ' is-invalid' : '' }}" name="USUARI_Clave_____b" placeholder="Contraseña" required>
                    @if ($errors->has('USUARI_Clave_____b'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('USUARI_Clave_____b') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox" name="" id="remember" {{ old('remember') ? 'checked' : '' }}> Recuerdame
                            </label>
                        </div>
                    </div>
                        
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Entrar</button>
                    </div>
                </div>
            </form>
            <!-- <a href="{{ route('password.request') }}">Olvide mi contraseña</a><br> -->
        </div>
    </div>
    

    <!-- jQuery 3 -->
    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
            });
        });
    </script>
</body>
</html>