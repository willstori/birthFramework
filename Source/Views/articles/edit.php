<!DOCTYPE html>
<html lang="pt-br">

<head>
    <base href="<?= APP_URL?>"/>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Artigo</title>
</head>

<body>
    <h1>Alteração de Artigo</h1>

    <form action="articles/<?= $article->id?>" method="post" style="display: flex; flex-direction: column; align-items: flex-start; gap: 5px;">
        <label>Título:</label>
        <input type="text" name="title" value="<?= $article->title ?>">
        
        <label>Texto:</label>
        <textarea name="text" rows="10"><?= $article->text ?></textarea>
        <button type="submit">Alterar</button>
    </form>

    <br>
    <a href="articles">Voltar</a>
</body>

</html>