<?php
require_once('Classe/Author.php');
$error = '';
$data = ['name' => '', 'country' => '', 'birth_year' => '', 'biography' => ''];
if(($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST'){
    foreach($data as $field => $value){
        $data[$field] = is_string($_POST[$field] ?? null) ? $_POST[$field] : '';
    }
    try{
        if(!(new Author)->insertAuthor($data)){
            throw new Exception("L'auteur n'a pas été enregistré.");
        }
        header('Location: author-index.php');
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
        <form action="author-create.php" method="post">
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
            <a href="author-index.php">Retour aux auteurs</a>
        </form>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
