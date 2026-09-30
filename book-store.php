<?php
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    header('location:book-index.php');
    die();
}

require_once('Classe/Book.php');

try{
    $crud = new Book;
    $insert = $crud->insertBook($_POST);

    if($insert){
        header("location:book-show.php?id=$insert");
        die();
    }else{
        echo "L'opération n'a pas été effectuée.";
    }
}catch(Exception $e){
    echo htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}

echo '<p><a href="index.php">Retour vers Accueil</a></p>';
