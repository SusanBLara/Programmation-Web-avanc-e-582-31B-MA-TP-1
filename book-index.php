<?php
require_once('Classe/Book.php');

$crud = new Book;
$books = $crud->all();
$categories = $crud->select('category', 'name');
$categoryNames = array();

foreach($categories as $category){
    $categoryNames[$category['id']] = $category['name'];
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livres</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once('includes/nav.php'); ?>
    <h1>Liste des livres</h1>
    <a href="book-create.php" class="btn">Ajouter un livre</a>

    <div class="catalogue-frame">
    <table class="books-table">
        <thead>
            <tr>
                <th class="book-title-column">Titre</th>
                <th>Auteur(s)</th>
                <th>Catégorie</th>
                <th class="book-description-column">Description</th>
                <th>Pages</th>
                <th>Prix</th>
                <th>Édition</th>
                <th>Âge</th>
            </tr>
        </thead>

        <tbody>
    <?php foreach($books as $book){ 
        $authors = $crud->authors($book['id']);
        $categoryName = 'Non renseignée';
        if(isset($categoryNames[$book['category_id']])){
            $categoryName = $categoryNames[$book['category_id']];
        }
    ?>
        <tr>
            <td>
                <a href="book-show.php?id=<?= $book['id']; ?>">
                    <?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </td>

            <td>
                <?php foreach($authors as $author){ ?>
                    <?= htmlspecialchars($author['name'], ENT_QUOTES, 'UTF-8'); ?><br>
                <?php } ?>
            </td>

            <td><?= htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars('' . $book['description'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= $book['page_count']; ?></td>
            <td class="book-price"><?= $book['price']; ?> $</td>
            <td><?= htmlspecialchars('' . $book['edition'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="book-age"><?= htmlspecialchars('' . $book['age_group'], ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
    <?php } ?>
</tbody>
    </table>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
