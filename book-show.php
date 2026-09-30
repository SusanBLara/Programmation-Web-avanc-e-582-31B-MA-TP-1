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

$authors = $crud->authors($id);
$category = $crud->selectId('category', $category_id);
$categoryName = '';
if($category){
    $categoryName = $category['name'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livre</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once('includes/nav.php'); ?>
    <div class="container">
        <h1>Détails du livre</h1>

        <p><strong>Titre : </strong><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Description : </strong><?= htmlspecialchars('' . $description, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Nombre de pages : </strong><?= $page_count; ?></p>
        <p><strong>Prix : </strong><?= $price; ?> $</p>
        <p><strong>Édition : </strong><?= htmlspecialchars('' . $edition, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Âge : </strong><?= htmlspecialchars('' . $age_group, ENT_QUOTES, 'UTF-8'); ?></p>

        <p><strong>Catégorie : </strong><?= htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Auteur(s) : </strong>
            <?php foreach($authors as $author){ ?>
                <?= htmlspecialchars($author['name'], ENT_QUOTES, 'UTF-8'); ?><br>
            <?php } ?>
        </p>

        <a href="book-edit.php?id=<?= $id; ?>" class="btn">Modifier</a>

        <form action="book-delete.php" method="post">
            <input type="hidden" name="id" value="<?= $id; ?>">
            <input type="submit" value="Supprimer" class="btn red">
        </form>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
