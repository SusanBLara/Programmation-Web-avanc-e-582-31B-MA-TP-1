<?php

session_start();

$draft = array('title' => '', 'description' => '', 'page_count' => '', 'price' => '',
    'edition' => '', 'age_group' => '', 'category_id' => '', 'authors' => array());

if(isset($_GET['resume']) && $_GET['resume'] == '1' && isset($_SESSION['book_draft'])){
    foreach($draft as $field => $value){
        if(isset($_SESSION['book_draft'][$field])){
            $draft[$field] = $_SESSION['book_draft'][$field];
        }
    }
}

require_once('Classe/Category.php');
require_once('Classe/Author.php');

$authorModel = new Author;
$authors = $authorModel->all();

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
                <input type="text" name="title" required maxlength="150" value="<?= htmlspecialchars($draft['title'], ENT_QUOTES, 'UTF-8'); ?>">
            </label>

            <fieldset>
                <legend>Auteur(s) — choisir au moins un auteur</legend>
                <?php foreach($authors as $author){ ?>
                    <label>
                        <input type="checkbox" name="authors[]" value="<?= $author['id']; ?>" <?php foreach($draft['authors'] as $selectedId){
                            if($author['id'] == $selectedId){ echo 'checked'; }
                        } ?>>
                        <?= htmlspecialchars($author['name'], ENT_QUOTES, 'UTF-8'); ?>
                    </label>
                <?php } ?>
                <?php if(!$authors){ ?><p>Aucun auteur enregistré.</p><?php } ?>
                <button type="submit" class="btn" formaction="author-create.php?from=book" name="save_book_draft" value="1" formnovalidate>Ajouter un auteur</button>
            </fieldset>

            <label>Description
                <input type="text" name="description" value="<?= htmlspecialchars($draft['description'], ENT_QUOTES, 'UTF-8'); ?>">
            </label>

            <label>Nombre de pages
                <input type="number" name="page_count" min="1" max="2147483647" value="<?= htmlspecialchars($draft['page_count'], ENT_QUOTES, 'UTF-8'); ?>">
            </label>

            <label>Prix
                <input type="number" step="0.01" name="price" required min="0" max="99999999.99" value="<?= htmlspecialchars($draft['price'], ENT_QUOTES, 'UTF-8'); ?>">
            </label>

            <label>Édition
                <input type="text" name="edition" maxlength="100" value="<?= htmlspecialchars($draft['edition'], ENT_QUOTES, 'UTF-8'); ?>">
            </label>

            <label>Âge
                <input type="text" name="age_group" maxlength="50" value="<?= htmlspecialchars($draft['age_group'], ENT_QUOTES, 'UTF-8'); ?>">
            </label>

            <label>Catégorie
                <select name="category_id">
                    <?php foreach($categories as $category){ ?>
                        <option value="<?= $category['id']; ?>" <?php if($draft['category_id'] == $category['id']){ echo 'selected'; } ?>>
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
