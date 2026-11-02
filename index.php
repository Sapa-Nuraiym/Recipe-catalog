<?php
require_once 'config/db.php';

$stmt = $pdo->query("
    SELECT recipes.*, categories.name AS category_name
    FROM recipes
    LEFT JOIN categories
    ON recipes.category_id = categories.id
    ORDER BY recipes.id DESC
");

$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Дәмді ас</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<section class="hero">
    <div class="container">
        <h1>Дәмді ас әлеміне қош келдіңіз!</h1>
        <p>Өзіңізге ұнайтын тағамдарды тауып, жаңа рецепттерді үйреніңіз.</p>
        <a href="recipes.php" class="btn">Рецепттерді көру</a>
    </div>
</section>

<section class="recipes-section container">
    <h2>Танымал рецепттер</h2>

    <div class="recipe-list">

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

                <span><?= h($recipe['category_name'] ?? 'Санат жоқ') ?></span>

            </div>

        <?php endforeach; ?>

    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>