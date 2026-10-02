<?php
require '../config/database.php';
require '../includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("
        SELECT id, username, password, role, anggota_id
        FROM users
        WHERE username = ?
        LIMIT 1
    ");

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
            'anggota_id' => $user['anggota_id'],
        ];

        header("Location: " . BASE . "/dashboard.php");
        exit;
    }

    $error = "Username atau password salah.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Perpustakaan</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                linear-gradient(135deg, #2563eb, #7c3aed);
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.97);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
        }

        .logo {
            width: 70px;
            height: 70px;
            margin: 0 auto 20px;
            border-radius: 18px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
        }

        h1 {
            text-align: center;
            color: #1e293b;
            font-size: 26px;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #334155;
            font-size: 14px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
            transition: 0.2s;
        }

        input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .error {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 18px;
            text-align: center;
        }

        button {
            width: 100%;
            border: none;
            padding: 14px;
            border-radius: 10px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            color: white;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        .footer {
            text-align: center;
            margin-top: 22px;
            color: #94a3b8;
            font-size: 12px;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 30px 22px;
            }
        }
    </style>
</head>

<body>

<div class="login-container">

    <div class="login-card">

        <div class="logo">📚</div>

        <h1>Perpustakaan</h1>
        <p class="subtitle">Silakan masuk ke akun Anda</p>

        <?php if ($error): ?>
            <div class="error">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="post">

            <div class="form-group">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Masukkan username"
                    autocomplete="username"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit">
                Masuk
            </button>

        </form>

        <div class="footer">
            Sistem Informasi Perpustakaan
        </div>

    </div>

</div>

</body>
</html>
