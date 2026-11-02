<?php
require_once 'config/db.php';

$search = trim($_GET['search'] ?? '');
$category = $_GET['category'] ?? '';

$sql = "
    SELECT recipes.*, categories.name AS category_name
    FROM recipes
    LEFT JOIN categories
    ON recipes.category_id = categories.id
    WHERE recipes.title LIKE ?
";

$params = ["%$search%"];

if ($category !== '') {
    $sql .= " AND recipes.category_id = ?";
    $params[] = $category;
}

$sql .= " ORDER BY recipes.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categories = $pdo->query(
    "SELECT * FROM categories"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Рецепттер каталогы</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<section class="container recipes-section">

    <h1>Рецепттер каталогы</h1>

    <form method="GET" class="search-form">

        <input
            type="text"
            name="search"
            placeholder="Рецепт іздеу..."
            value="<?= h($search) ?>"
        >

        <select name="category">
            <option value="">Барлық санаттар</option>

            <?php foreach ($categories as $cat): ?>

                <option
                    value="<?= $cat['id'] ?>"
                    <?= (string)$category === (string)$cat['id'] ? 'selected' : '' ?>
                >
                    <?= h($cat['name']) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <button type="submit" class="btn">Іздеу</button>

    </form>

    <div class="recipe-list">

        <?php if (count($recipes) > 0): ?>

            <?php foreach ($recipes as $recipe): ?>

                <div class="recipe-card">

                    <div class="recipe-image">
                       <?php
                        $title = mb_strtolower($recipe['title']);

                        if (str_contains($title, 'құймақ')) {
                            echo '🥞';
                        } elseif (str_contains($title, 'салат')) {
                            echo '🥗';
                        } elseif (str_contains($title, 'сорпа')) {
                            echo '🍲';
                        } elseif (str_contains($title, 'торт')) {
                            echo '🎂';
                        } else {
                            echo '🍽️';
                        }
                        ?>
                    </div>

                    <h3><?= h($recipe['title']) ?></h3>

                    <p><?= h($recipe['description']) ?></p>

                    <p><strong>Санат:</strong>
                        <?= h($recipe['category_name'] ?? 'Санат жоқ') ?>
                    </p>

                    <details>
                        <summary>Рецептті оқу</summary>

                        <p><strong>Құрамы:</strong></p>
                        <p><?= h($recipe['ingredients']) ?></p>

                        <p><strong>Дайындалуы:</strong></p>
                        <p><?= h($recipe['instructions']) ?></p>
                    </details>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <p>Рецепт табылмады.</p>

        <?php endif; ?>

    </div>

</section>

<?php include 'includes/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>