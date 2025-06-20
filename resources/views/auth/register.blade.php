<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Wedding Planner</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Great+Vibes&family=Montserrat:wght@400;500&display=swap');

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #fce4ec, #fff0f5);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .register-container {
            background-color: #fff;
            padding: 30px 35px;
            border-radius: 15px;
            box-shadow: 0 6px 18px rgba(255, 182, 193, 0.25);
            width: 100%;
            max-width: 450px;
            position: relative;
        }

        h2 {
            font-family: 'Great Vibes', cursive;
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: #d94f6b;
            text-align: center;
            text-shadow: 1px 1px 2px #f6c1c9;
        }

        p.subtitle {
            font-style: italic;
            color: #a35b6b;
            margin-bottom: 25px;
            font-size: 0.95rem;
            text-align: center;
        }

        .alert {
            background-color: #ffe6e6;
            border: 1px solid #ff4d4d;
            color: #b30000;
            padding: 10px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .input-group {
            position: relative;
            margin-bottom: 18px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea,
        select {
            width: 100%;
            padding: 12px 16px;
            border-radius: 10px;
            border: 1.5px solid #e8b9c1;
            font-size: 0.95rem;
            outline: none;
            transition: 0.3s ease;
            box-sizing: border-box;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #d94f6b;
            box-shadow: 0 0 5px #d94f6b66;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: #d94f6b;
        }

        textarea {
            resize: vertical;
            min-height: 70px;
        }

        select {
            background-color: #fff;
            cursor: pointer;
        }

        button {
            background: linear-gradient(45deg, #d94f6b, #c1455d);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            margin-top: 10px;
            box-shadow: 0 4px 10px #d94f6b88;
            transition: background 0.3s ease;
        }

        button:hover {
            background: linear-gradient(45deg, #b84459, #9c3c4a);
        }

        .floral-corner {
            position: absolute;
            top: -20px;
            right: -20px;
            width: 70px;
            height: 70px;
            background: url('https://i.ibb.co/mzD7gGt/floral-corner.png') no-repeat center/contain;
            opacity: 0.3;
            pointer-events: none;
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 25px;
                max-width: 360px;
            }

            h2 {
                font-size: 2.2rem;
            }
        }
    </style>
</head>
<body>

<div class="register-container">
    <div class="floral-corner"></div>
    <h2>Wedding Planner</h2>
    <p class="subtitle">Isi data untuk membuat akun baru</p>

    @if($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ url('register') }}">
        @csrf

        <div class="input-group">
            <input type="text" name="nama" placeholder="Nama" required>
        </div>

        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-group">
            <input type="text" name="no_hp" placeholder="No HP" required>
        </div>

        <div class="input-group">
            <textarea name="alamat" placeholder="Alamat" required></textarea>
        </div>

        <div class="input-group">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
        </div>

        <div class="input-group">
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Password" required>
            <span class="toggle-password" onclick="togglePassword('password_confirmation')">👁️</span>
        </div>

        <div class="input-group">
            <select name="role" required>
                <option value="">-- Pilih Peran --</option>
                <option value="admin">Admin</option>
                <option value="vendor">Vendor</option>
                <option value="klien">Klien</option>
            </select>
        </div>

        <button type="submit">Register</button>
    </form>
</div>

<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        input.type = input.type === "password" ? "text" : "password";
    }
</script>

</body>
</html>
