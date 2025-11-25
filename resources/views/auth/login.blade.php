<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Psico Alianza</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            overflow: hidden;
        }

        .login-container {
            display: flex;
            height: 100vh;
        }

        .login-left {
            flex: 1;
            background: linear-gradient(135deg, rgba(67, 57, 242, 0.85) 0%, rgba(67, 57, 242, 0.75) 100%),
            url('{{ asset('images/login-background.jpg') }}');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 4rem;
            color: white;
            position: relative;
        }

        .login-content {
            position: relative;
            z-index: 1;
            max-width: 500px;
        }

        .login-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .login-subtitle {
            font-size: 1.25rem;
            font-weight: 300;
            opacity: 0.95;
        }

        .login-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            padding: 2rem;
        }

        .login-form-container {
            width: 100%;
            max-width: 450px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 3rem;
        }

        .logo-container img {
            max-width: 280px;
            height: auto;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 0.875rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #4339F2;
            box-shadow: 0 0 0 3px rgba(67, 57, 242, 0.1);
        }

        .password-field {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            padding: 0.5rem;
            transition: color 0.3s;
        }

        .password-toggle:hover {
            color: #4339F2;
        }

        .remember-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .remember-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #4339F2;
        }

        .btn-login {
            width: 100%;
            background: #4339F2;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.875rem;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
        }

        .btn-login:hover {
            background: #3730cc;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 57, 242, 0.3);
        }

        .login-links {
            display: flex;
            justify-content: space-between;
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }

        .login-links a {
            color: #4339F2;
            text-decoration: none;
            transition: all 0.3s;
        }

        .login-links a:hover {
            text-decoration: underline;
            color: #3730cc;
        }

        @media (max-width: 992px) {
            .login-left {
                display: none;
            }

            .login-right {
                flex: 1;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <!-- Lado izquierdo con imagen -->
    <div class="login-left">
        <div class="login-content">
            <h1 class="login-title">Bienvenido a la mejor plataforma<br>organizacional online</h1>
            <p class="login-subtitle">Gestión efectiva del talento humano</p>
        </div>
    </div>

    <!-- Lado derecho con formulario -->
    <div class="login-right">
        <div class="login-form-container">
            <div class="logo-container">
                <img src="{{ asset('images/logo-psico-alianza.png') }}" alt="Psico Alianza">
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="username" class="form-label">Usuario</label>
                    <input type="text"
                           class="form-control"
                           id="username"
                           name="username"
                           placeholder="Pruebadesarrollador"
                           value="Pruebadesarrollador"
                           required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="password-field">
                        <input type="password"
                               class="form-control"
                               id="password"
                               name="password"
                               placeholder="••••••••••••"
                               required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="far fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="remember-checkbox">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember" style="cursor: pointer; font-weight: normal; color: #666;">
                        Recordar cuenta
                    </label>
                </div>

                <button type="submit" class="btn btn-login">
                    Iniciar sesión
                </button>

                <div class="login-links">
                    <a href="#">¿Olvidaste tu usuario?</a>
                    <a href="#">¿Olvidaste tu contraseña?</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>
</body>
</html>
