<!DOCTYPE html>
<html lang="pt-br">

<head>
    <base href="<?= APP_URL ?>" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Artigos</title>
</head>

<body>
    <h1>Lista de Artigos</h1>
    <table border="1">
        <thead>
            <tr>
                <th><button onclick="window.location.href='articles/criar'">Novo Artigo</button></th>
                <th>Título</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article) { ?>
                <tr>
                    <td>
                        <button onclick="window.location.href='articles/<?= $article->id ?>'">Editar</button>
                        <button onclick="window.location.href='articles/remover/<?= $article->id ?>'">Remover</button>
                    </td>
                    <td><?= $article->title ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <br>
    <a href="articles">Voltar</a>
</body>

</html>