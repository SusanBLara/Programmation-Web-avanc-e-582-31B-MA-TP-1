<?php
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    header('location:book-index.php');
    die();
}

// Accepte seulement un identifiant entier positif.
$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if($id === false){
    header('location:book-index.php');
    die();
}

require_once('Classe/Book.php');

try{
    $crud = new Book;
    $update = $crud->updateBook($_POST);

    if($update){
        header("location:book-show.php?id=$id");
        die();
    }else{
        echo "L'opération n'a pas été effectuée.";
    }
}catch(Exception $e){
    echo htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}

echo '<p><a href="index.php">Retour vers Accueil</a></p>';
