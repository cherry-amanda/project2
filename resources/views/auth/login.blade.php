<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Wedding Planner</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Great+Vibes&family=Montserrat&display=swap');

        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background: linear-gradient(135deg, #f9e2e7, #fff7f9);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(255, 192, 203, 0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
            position: relative;
        }

        h2 {
            font-family: 'Great Vibes', cursive;
            font-size: 3rem;
            margin-bottom: 10px;
            color: #d94f6b;
            text-shadow: 1px 1px 3px #f6c1c9;
        }

        p.subtitle {
            font-style: italic;
            color: #a35b6b;
            margin-bottom: 25px;
            font-size: 1rem;
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .alert-success {
            background-color: #e6ffed;
            color: #2b6a28;
            border: 1px solid #5ad14b;
        }

        .alert-error {
            background-color: #ffe6e6;
            color: #b30000;
            border: 1px solid #ff4d4d;
        }

        .input-wrapper {
            position: relative;
            width: 100%;
            margin: 10px 0;
        }

        .input-wrapper input {
            width: 100%;
            padding: 12px 15px;
            padding-right: 40px;
            border-radius: 10px;
            border: 1.8px solid #dcb0b3;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            box-sizing: border-box;
        }

        .input-wrapper input:focus {
            border-color: #d94f6b;
            box-shadow: 0 0 8px #d94f6b88;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password svg {
            width: 18px;
            height: 18px;
            fill: #b84459;
        }

        button[type="submit"] {
            background: linear-gradient(45deg, #d94f6b, #b84459);
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            width: 100%;
            font-size: 1.1rem;
            box-shadow: 0 5px 15px #d94f6baa;
            transition: background 0.3s ease;
            margin-top: 12px;
        }

        button[type="submit"]:hover {
            background: linear-gradient(45deg, #b84459, #9c3c4a);
        }

        a {
            display: block;
            margin-top: 18px;
            color: #d94f6b;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            text-decoration: underline;
            color: #b84459;
        }

        .floral-corner {
            position: absolute;
            top: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            background: url('https://i.ibb.co/mzD7gGt/floral-corner.png') no-repeat center/contain;
            opacity: 0.35;
            pointer-events: none;
        }

        @media (max-width: 440px) {
            .login-container {
                padding: 25px 20px;
                max-width: 320px;
            }
            h2 {
                font-size: 2.4rem;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="floral-corner"></div>
    <h2>Wedding Planner</h2>
    <p class="subtitle">Silakan login untuk melanjutkan</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ url('login') }}">
        @csrf

        <div class="input-wrapper">
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-wrapper">
            <input type="password" name="password" placeholder="Password" id="password" required>
            <button type="button" class="toggle-password" onclick="togglePassword()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                    <path d="M572.52 241.4C518.15 135.6 407.62 64 288 64 168.4 64 57.84 135.6 3.48 241.4a48.09 48.09 0 0 0 0 29.2C57.84 376.4 168.4 448 288 448c119.6 0 230.2-71.6 284.52-177.4a48.09 48.09 0 0 0 0-29.2zM288 400c-97.05 0-192.25-57.3-240-144 47.75-86.7 142.95-144 240-144s192.25 57.3 240 144c-47.75 86.7-142.95 144-240 144zm0-240a96 96 0 1 0 96 96 95.99 95.99 0 0 0-96-96zm0 144a48 48 0 1 1 48-48 47.99 47.99 0 0 1-48 48z"/>
                </svg>
            </button>
        </div>

        <button type="submit">Login</button>
        <a href="{{ route('register') }}">Belum punya akun? Daftar</a>
        <a href="{{ route('landing') }}">kembali</a>
    </form>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
    }
</script>

</body>
</html>
