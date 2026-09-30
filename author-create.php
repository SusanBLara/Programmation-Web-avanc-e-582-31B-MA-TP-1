<?php

session_start();
$fromBook = false;

if(isset($_GET['from']) && $_GET['from'] == 'book'){
    $fromBook = true;
}

$formAction = 'author-create.php';
if($fromBook){
    $formAction = 'author-create.php?from=book';
}

// Garde les champs du livre pendant l'ajout d'un auteur.
if($fromBook && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_book_draft'])){
    $draft = array();

    foreach(array('title', 'description', 'page_count', 'price', 'edition', 'age_group', 'category_id') as $field){
        $draft[$field] = '';

        if(isset($_POST[$field]) && is_string($_POST[$field])){
            $draft[$field] = $_POST[$field];
        }
    }

    $draft['authors'] = array();

    if(isset($_POST['authors']) && is_array($_POST['authors'])){

        foreach($_POST['authors'] as $authorId){
            if(is_string($authorId)){
                $draft['authors'][] = $authorId;
            }
        }
    }

    $_SESSION['book_draft'] = $draft;
    header('Location: author-create.php?from=book');
    exit;
}

require_once('Classe/Author.php');

$error = '';
$data = array('name' => '', 'country' => '', 'birth_year' => '', 'biography' => '');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    foreach($data as $field => $value){
        if(isset($_POST[$field]) && is_string($_POST[$field])){
            $data[$field] = $_POST[$field];
        }
    }
    try{
        $authorModel = new Author;
        $authorId = $authorModel->insertAuthor($data);
        if(!$authorId){
            throw new Exception("L'auteur n'a pas été enregistré.");
        }
        if($fromBook){

            // Sélectionne le nouvel auteur et reprend la création du livre.
            $_SESSION['book_draft']['authors'][] = $authorId;
            header('Location: book-create.php?resume=1');
        }else{
            header('Location: author-index.php');
        }
        exit;
        
    }catch(Exception $e){
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvel auteur</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once('includes/nav.php'); ?>
    <div class="container">
        <form action="<?= $formAction; ?>" method="post">
            <h2>Nouvel auteur</h2>
            <?php if($error !== ''){ ?><p role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p><?php } ?>
            <label>Nom
                <input type="text" name="name" required maxlength="100" value="<?= htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8'); ?>">
            </label>
            <label>Pays (facultatif)
                <input type="text" name="country" maxlength="100" value="<?= htmlspecialchars($data['country'], ENT_QUOTES, 'UTF-8'); ?>">
            </label>
            <label>Année de naissance (facultatif)
                <input type="number" name="birth_year" min="1" max="<?= date('Y'); ?>" step="1" value="<?= htmlspecialchars($data['birth_year'], ENT_QUOTES, 'UTF-8'); ?>">
            </label>
            <label>Biographie (facultatif)
                <textarea name="biography" rows="4" maxlength="16000"><?= htmlspecialchars($data['biography'], ENT_QUOTES, 'UTF-8'); ?></textarea>
            </label>
            <input type="submit" class="btn" value="Enregistrer">
            <?php if($fromBook){ ?>
                <a href="book-create.php?resume=1">Retour au livre</a>
            <?php }else{ ?>
                <a href="author-index.php">Retour aux auteurs</a>
            <?php } ?>
        </form>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
