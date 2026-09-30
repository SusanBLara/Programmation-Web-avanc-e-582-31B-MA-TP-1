<?php
require_once('Classe/Category.php');
$model = new Category;
$categories = $model->all();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catégories</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once('includes/nav.php'); ?>
    <h1>Catégories de livres</h1>
    <table class="categories-table">
        <thead><tr><th>Nom</th><th>Description</th></tr></thead>
        <tbody>
            <?php foreach($categories as $category){ ?>
                <tr><td><?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?></td><td><?= htmlspecialchars('' . $category['description'], ENT_QUOTES, 'UTF-8'); ?></td></tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
