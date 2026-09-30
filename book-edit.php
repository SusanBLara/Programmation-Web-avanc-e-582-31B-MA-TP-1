<?php

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

if($id === false){
    header('location:book-index.php');
    die();
}

require_once('Classe/Book.php');

$crud = new Book;
$book = $crud->find($id);

if($book){
    extract($book);
}else{
    header('location:book-index.php');
    die();
}

require_once('Classe/Category.php');
require_once('Classe/Author.php');

$categoryModel = new Category;
$categories = $categoryModel->all();
$authorModel = new Author;
$authors = $authorModel->all();
$selectedAuthors = $crud->authors($id);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un livre</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once('includes/nav.php'); ?>
    <div class="container">
        <form action="book-update.php" method="post">
            <h2>Modifier le livre</h2>

            <input type="hidden" name="id" value="<?= $id; ?>">

            <label>Titre
                <input type="text" name="title" required maxlength="150" value="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>">
            </label>

            <fieldset>
                <legend>Auteur(s) - choisir au moins un auteur</legend>
                <?php foreach($authors as $author){ ?>
                    <label>
                        <input type="checkbox" name="authors[]" value="<?= $author['id']; ?>"
                            <?php foreach($selectedAuthors as $selectedAuthor){
                                if($author['id'] == $selectedAuthor['id']){
                                    echo 'checked';
                                }
                            } ?>>
                        <?= htmlspecialchars($author['name'], ENT_QUOTES, 'UTF-8'); ?>
                    </label>
                <?php } ?>
            </fieldset>

            <label>Description
                <input type="text" name="description" value="<?= htmlspecialchars('' . $description, ENT_QUOTES, 'UTF-8'); ?>">
            </label>

            <label>Nombre de pages
                <input type="number" name="page_count" min="1" max="2147483647" value="<?= $page_count; ?>">
            </label>

            <label>Prix
                <input type="number" step="0.01" name="price" required min="0" max="99999999.99" value="<?= $price; ?>">
            </label>

            <label>Édition
                <input type="text" name="edition" maxlength="100" value="<?= htmlspecialchars('' . $edition, ENT_QUOTES, 'UTF-8'); ?>">
            </label>

            <label>Âge
                <input type="text" name="age_group" maxlength="50" value="<?= htmlspecialchars('' . $age_group, ENT_QUOTES, 'UTF-8'); ?>">
            </label>

            <label>Catégorie
                <select name="category_id">
                    <?php foreach($categories as $category){ ?>
                        <option
                            value="<?= $category['id']; ?>"
                            <?php if($category['id'] == $category_id){ echo 'selected'; } ?>>
                            <?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php } ?>
                </select>
            </label>

            <input type="submit" class="btn" value="Enregistrer">
        </form>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
