<?php
require_once 'config/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $error = "Барлық жолдарды толтырыңыз!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email дұрыс енгізілмеген!";
    } elseif (strlen($password) < 6) {
        $error = "Құпия сөз кемінде 6 таңбадан тұруы керек!";
    } else {

        $check = $pdo->prepare(
            "SELECT id FROM users WHERE email = ?"
        );
        $check->execute([$email]);

        if ($check->fetch()) {
            $error = "Бұл email бұрын тіркелген!";
        } else {

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $pdo->prepare(
                "INSERT INTO users (name, email, password)
                 VALUES (?, ?, ?)"
            );

            $stmt->execute([
                $name,
                $email,
                $hashedPassword
            ]);

            header("Location: login.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>Тіркелу</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="form-container">
    <h2>Тіркелу</h2>

    <?php if ($error): ?>
        <p class="error"><?= h($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Аты-жөніңіз" required>

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Құпия сөз" required>

        <button type="submit" class="btn">Тіркелу</button>
    </form>

    <p>Аккаунтыңыз бар ма? <a href="login.php">Кіру</a></p>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>