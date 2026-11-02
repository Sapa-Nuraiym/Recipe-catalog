
<?php
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $ingredients = trim($_POST['ingredients'] ?? '');
    $instructions = trim($_POST['instructions'] ?? '');
    $category_id = $_POST['category_id'] ?? '';

    if (
        $title === '' ||
        $description === '' ||
        $ingredients === '' ||
        $instructions === '' ||
        $category_id === ''
    ) {
        $error = "Барлық жолдарды толтырыңыз!";
    } else {

        $check = $pdo->prepare(
            "SELECT id FROM categories WHERE id = ?"
        );
        $check->execute([$category_id]);

        if (!$check->fetch()) {
            $error = "Санатты дұрыс таңдаңыз!";
        } else {

            $stmt = $pdo->prepare(
                "INSERT INTO recipes
                (title, description, ingredients, instructions, category_id)
                VALUES (?, ?, ?, ?, ?)"
            );

            $stmt->execute([
                $title,
                $description,
                $ingredients,
                $instructions,
                $category_id
            ]);

            header("Location: recipes.php");
            exit;
        }
    }
}

$categories = $pdo->query(
    "SELECT * FROM categories"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Рецепт қосу - Дәмді ас</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="form-container recipe-form">

    <h2>🍽 Жаңа рецепт қосу</h2>

    <p class="form-description">
        Өзіңіздің сүйікті рецептіңізбен бөлісіңіз!
    </p>

    <?php if ($error): ?>
        <p class="error"><?= h($error) ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Тағам атауы</label>
        <input
            type="text"
            name="title"
            placeholder="Мысалы: Қазақша ет"
            required
        >

        <label>Қысқаша сипаттамасы</label>
        <textarea
            name="description"
            placeholder="Тағам туралы қысқаша ақпарат..."
            required
        ></textarea>

        <label>Ингредиенттері</label>
        <textarea
            name="ingredients"
            placeholder="Қажетті өнімдерді жазыңыз..."
            required
        ></textarea>

        <label>Дайындалу жолы</label>
        <textarea
            name="instructions"
            placeholder="Тағамның дайындалу ретін жазыңыз..."
            required
        ></textarea>

        <label>Тағам санаты</label>
        <select name="category_id" required>
            <option value="">Санатты таңдаңыз</option>

            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>">
                    <?= h($category['name']) ?>
                </option>
            <?php endforeach; ?>

        </select>

        <button type="submit" class="btn">
            + Рецептті сақтау
        </button>

    </form>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
