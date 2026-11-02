<?php
require_once 'config/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare(
        "SELECT * FROM users WHERE email = ?"
    );

    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header("Location: index.php");
        exit;

    } else {
        $error = "Email немесе құпия сөз қате!";
    }
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>Жүйеге кіру</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="form-container">
    <h2>Жүйеге кіру</h2>

    <?php if ($error): ?>
        <p class="error"><?= h($error) ?></p>
    <?php endif; ?>

    <form method="POST">

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Құпия сөз" required>

        <button type="submit" class="btn">Кіру</button>

    </form>

    <p>Аккаунтыңыз жоқ па? <a href="register.php">Тіркелу</a></p>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>