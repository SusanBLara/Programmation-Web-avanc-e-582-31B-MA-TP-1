<?php

require_once('Classe/Author.php');

$authorModel = new Author;
$authors = $authorModel->all();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auteurs</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once('includes/nav.php'); ?>
    <h1>Liste des auteurs</h1>
    <div class="catalogue-frame">
        <table>
            <thead><tr><th>Nom</th><th>Pays</th><th>Année de naissance</th><th>Biographie</th></tr></thead>
            <tbody>
                <?php foreach($authors as $author){ ?>
                    <tr>
                        <?php foreach(array('name', 'country', 'birth_year', 'biography') as $field){ ?>
                            <td><?php
                                if(isset($author[$field])){
                                    echo htmlspecialchars('' . $author[$field], ENT_QUOTES, 'UTF-8');
                                }else{
                                    echo 'Non renseigné';
                                }
                            ?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
                <?php if(!$authors){ ?><tr><td colspan="4">Aucun auteur enregistré.</td></tr><?php } ?>
            </tbody>
        </table>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
