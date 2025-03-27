<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $password == $user['password']) {

        $_SESSION['user'] = $user;
        header("Location: index.php");
        exit();
    } else {
        $error = "Tên đăng nhập hoặc mật khẩu sai!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Đăng nhập</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <label>Tên đăng nhập:</label>
        <input type="text" name="username" required>
        <br>
        <label>Mật khẩu:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Đăng nhập</button>
    </form>
</body>
</html>
