<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header("Location: dashboar.php");
        exit();
    } else {
        $error = 'Username atau password salah!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Kasir</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to right, #dbeafe, #bfdbfe);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
      color: #333;
    }

    .container {
      background: #ffffff;
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 420px;
    }

    h2 {
      text-align: center;
      color: #1d4ed8;
      margin-bottom: 24px;
      font-size: 26px;
    }

    .error {
      background: #fdecea;
      color: #d32f2f;
      font-weight: 600;
      padding: 10px 16px;
      margin-bottom: 16px;
      border: 1px solid #f5c6cb;
      border-radius: 8px;
      text-align: center;
    }

    .form-group {
      margin-bottom: 18px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      color: #1e3a8a;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      border: 2px solid #60a5fa;
      border-radius: 8px;
      font-size: 15px;
      transition: 0.3s;
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
      outline: none;
      border-color: #2563eb;
      box-shadow: 0 0 8px rgba(37, 99, 235, 0.3);
    }

    button[type="submit"] {
      width: 100%;
      background-color: #2563eb;
      color: white;
      border: none;
      border-radius: 8px;
      padding: 14px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s, box-shadow 0.3s;
    }

    button[type="submit"]:hover {
      background-color: #1e40af;
      box-shadow: 0 5px 15px rgba(30, 64, 175, 0.4);
    }

    @media (max-width: 480px) {
      .container {
        padding: 30px 20px;
      }

      h2 {
        font-size: 22px;
      }

      button[type="submit"] {
        font-size: 15px;
        padding: 12px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Login Kasir</h2>

    <?php if ($error): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" placeholder="Masukkan username" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Masukkan password" required>
      </div>

      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
