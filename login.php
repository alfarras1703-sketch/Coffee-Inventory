<?php
session_start();
require_once "config/database.php";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $usernameSafe = mysqli_real_escape_string($conn, $username);
        $passwordHash = md5($password);

        $query = mysqli_query(
            $conn,
            "SELECT id, username, password, name
             FROM users
             WHERE username = '$usernameSafe'
             LIMIT 1"
        );

        $user = mysqli_fetch_assoc($query);

        if ($user && $user['password'] === $passwordHash) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];

            header("Location: index.php");
            exit;
        }

        $error = 'Username atau password salah.';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Coffee Inventory</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            min-height: 100vh;
            background: #151515;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
        }

        .login-wrapper {
            width: 100%;
            max-width: 920px;
            min-height: 560px;
            background: #fff;
            border-radius: 24px;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.35);
            animation: showLogin 0.7s ease;
        }

        .login-brand {
            background: #151515;
            color: #fff;
            padding: 55px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-brand::before {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 50%;
            top: -130px;
            right: -130px;
        }

        .login-brand::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 50%;
            bottom: -100px;
            left: -100px;
        }

        .brand-logo {
            width: 90px;
            height: 90px;
            background: #fff;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 28px;
            position: relative;
            z-index: 1;
            box-shadow: 0 12px 30px rgba(0,0,0,0.25);
        }

        .brand-logo img {
            width: 65px;
            height: 65px;
            object-fit: contain;
        }

        .login-brand h1 {
            font-size: 32px;
            line-height: 1.15;
            margin-bottom: 14px;
            position: relative;
            z-index: 1;
        }

        .login-brand p {
            color: #aaa;
            font-size: 14px;
            line-height: 1.7;
            max-width: 330px;
            position: relative;
            z-index: 1;
        }

        .login-form-area {
            padding: 55px;
            display: flex;
            align-items: center;
        }

        .login-form {
            width: 100%;
        }

        .login-form h2 {
            font-size: 28px;
            color: #171717;
            margin-bottom: 8px;
        }

        .login-form .subtitle {
            color: #888;
            font-size: 13px;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;
            height: 48px;
            padding: 0 15px;
            border: 1px solid #ddd;
            border-radius: 11px;
            outline: none;
            font-family: inherit;
            font-size: 14px;
            color: #222;
            background: #fff;
            transition: all 0.2s ease;
        }

        .input-wrapper input:focus {
            border-color: #222;
            box-shadow: 0 0 0 4px rgba(0,0,0,0.05);
        }

        .input-wrapper input::placeholder {
            color: #aaa;
        }

        .error-message {
            background: #f5f5f5;
            border-left: 4px solid #222;
            color: #333;
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            animation: shake 0.35s ease;
        }

        .btn-login {
            width: 100%;
            height: 48px;
            border: none;
            border-radius: 11px;
            background: #171717;
            color: #fff;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-login:hover {
            background: #333;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            margin-top: 28px;
            color: #aaa;
            font-size: 11px;
        }

        @keyframes showLogin {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes shake {
            0%, 100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        @media (max-width: 750px) {
            body {
                overflow: auto;
            }

            .login-wrapper {
                grid-template-columns: 1fr;
                max-width: 480px;
            }

            .login-brand {
                padding: 35px;
                min-height: 300px;
            }

            .login-brand h1 {
                font-size: 26px;
            }

            .brand-logo {
                width: 70px;
                height: 70px;
                border-radius: 18px;
                margin-bottom: 20px;
            }

            .brand-logo img {
                width: 50px;
                height: 50px;
            }

            .login-form-area {
                padding: 35px;
            }
        }

        @media (max-width: 450px) {
            body {
                padding: 12px;
            }

            .login-brand,
            .login-form-area {
                padding: 28px;
            }

            .login-wrapper {
                border-radius: 18px;
            }
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <div class="login-brand">

        <div class="brand-logo">
            <img src="assets/icon/Logo coffee inventoy.png" alt="Coffee Inventory">
        </div>

        <h1>Coffee<br>Inventory</h1>

        <p>
            Sistem manajemen produk dan stok
            untuk membantu pengelolaan inventory
            secara lebih mudah dan terorganisir.
        </p>

    </div>

    <div class="login-form-area">

        <form method="POST" class="login-form">

            <h2>Selamat Datang</h2>

            <p class="subtitle">
                Silakan masuk untuk mengakses dashboard.
            </p>

            <?php if ($error !== ''): ?>
                <div class="error-message">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="username">Username</label>

                <div class="input-wrapper">
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Masukkan username"
                        autocomplete="username"
                        required
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>

                <div class="input-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="btn-login">
                Masuk ke Dashboard
            </button>

            <div class="login-footer">
                Coffee Inventory &copy; 2026
            </div>

        </form>

    </div>

</div>

</body>
</html>