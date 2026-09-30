<?php
require_once('Classe/Author.php');
$authors = (new Author)->all();
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
    <a href="author-create.php" class="btn">Ajouter un auteur</a>
    <div class="catalogue-frame">
        <table>
            <thead><tr><th>Nom</th><th>Pays</th><th>Année de naissance</th><th>Biographie</th></tr></thead>
            <tbody>
                <?php foreach($authors as $author){ ?>
                    <tr>
                        <?php foreach(['name', 'country', 'birth_year', 'biography'] as $field){ ?>
                            <td><?= htmlspecialchars((string) ($author[$field] ?? 'Non renseigné'), ENT_QUOTES, 'UTF-8'); ?></td>
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
