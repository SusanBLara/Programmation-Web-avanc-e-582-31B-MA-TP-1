<?php
require_once('Classe/Category.php');

$categoryModel = new Category;
$categories = $categoryModel->all();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau livre</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once('includes/nav.php'); ?>
    <div class="container">
        <form action="book-store.php" method="post">
            <h2>Nouveau livre</h2>

            <label>Titre
                <input type="text" name="title" required maxlength="150">
            </label>

            <label for="author-1">Auteur principal
                <input type="text" id="author-1" name="author_1" maxlength="100" required>
            </label>

            <label for="author-2">Deuxième auteur (facultatif)
                <input type="text" id="author-2" name="author_2" maxlength="100">
            </label>

            <label for="author-3">Troisième auteur (facultatif)
                <input type="text" id="author-3" name="author_3" maxlength="100">
            </label>

            <label>Description
                <input type="text" name="description">
            </label>

            <label>Nombre de pages
                <input type="number" name="page_count" min="1" max="2147483647">
            </label>

            <label>Prix
                <input type="number" step="0.01" name="price" required min="0" max="99999999.99">
            </label>

            <label>Édition
                <input type="text" name="edition" maxlength="100">
            </label>

            <label>Âge
                <input type="text" name="age_group" maxlength="50">
            </label>

            <label>Catégorie
                <select name="category_id">
                    <?php foreach($categories as $category){ ?>
                        <option value="<?= $category['id']; ?>">
                            <?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php } ?>
                </select>
            </label>
        
            <input type="submit" class="btn" value="Enregistrer">
        </form>
    </div>
</body>
</html>
