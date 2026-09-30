<?php
require_once('Classe/Book.php');

$crud = new Book;
$books = $crud->all();

// Les couvertures disponibles sont associées aux titres des livres.
$bookImages = array(
    "L'Histoire sans fin" => "images/L'histoir.jpg",
    'Le Petit Chaperon rouge' => 'images/La-petite-chaperon.jpg',
    'Le Petit Prince' => 'images/L-petit-prince.jpg',
    'Les Aventures de Tom Sawyer' => 'images/Las-aventures-de-Tom.jpg',
    'Martine à la ferme' => 'images/Martin-a-la-ferme.jpg'
);
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
                <th>Image</th>
                <th class="book-title-column">Titre</th>
                <th>Auteur(s)</th>
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
        // Remet l'image à zéro pour ne pas garder celle du livre précédent.
        $bookImage = '';
        if(isset($bookImages[$book['title']])){
            $bookImage = $bookImages[$book['title']];
        }
    ?>
        <tr>
            <td>
                <?php
                if($bookImage != ''){ ?>
                    <a href="book-show.php?id=<?= $book['id']; ?>">
                        <img class="book-cover" src="<?= htmlspecialchars($bookImage, ENT_QUOTES, 'UTF-8'); ?>"
                             alt="Couverture de <?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8'); ?>">
                    </a>
                <?php }else{ ?>
                    <span class="cover-unavailable">Image non disponible</span>
                <?php } ?>
            </td>
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
</body>
</html>