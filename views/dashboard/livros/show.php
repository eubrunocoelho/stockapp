<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php
    require __DIR__ . '/../partials/head.php';
    ?>
</head>
<body>
    <div class="window-overlay">
    <?php
    require __DIR__ . '/../partials/side-navigation.php';
    ?>
    </div>
    <?php
    require __DIR__ . '/../partials/header.php';
    ?>
    <main class="main">
        <div class="main-heading container">
            <h1 class="main-heading__title">Livro: <?=$livro['titulo']?></h1>
            <span class="main-heading__breadcrumbs"><a class="breadcrumbs__link" href="<?=$basePath?>/livros/show/<?=$livro['ID']?>"><?=$livro['titulo']?></a> / <a class="breadcrumbs__link" href="<?=$basePath?>/livros">Livros</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/dashboard">Dashboard</a></span>
        </div>
        <section class="section container">
        <?php
            if (
                !(empty($messages)) &&
                (array_key_exists('message.warning', $messages))
            ) {
            ?>
            <div class="alert-box alert--warning mb--20">
                <p class="alert-box__text"><?=$messages['message.warning'][0]?></p>
            </div>
            <?php
            }
            ?>
            <?php
            if (
                !(empty($messages)) &&
                (array_key_exists('message.success', $messages))
            ) {
            ?>
            <div class="alert-box alert--success mb--20">
                <p class="alert-box__text"><?=$messages['message.success'][0]?></p>
            </div>
            <?php
            }
            ?>
            <div class="box">
                <div class="box-heading">
                    <h1 class="box-heading__title"><?=$livro['titulo']?></h1>
                </div>
                <table class="table mb--20">
                    <tbody>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Título</th>
                            <td class="table__cell fg--1"><?=$livro['titulo']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Autor(es)</th>
                            <td class="table__cell fg--1"><?=$livro['autor']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Editora(s)</th>
                            <td class="table__cell fg--1"><?=$livro['editora']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Formato</th>
                            <td class="table__cell fg--1"><?=$livro['formato']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Ano da Publicação</th>
                            <td class="table__cell fg--1"><?=$livro['ano_publicacao']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">ISBN</th>
                            <td class="table__cell fg--1"><?=$livro['isbn']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Edição</th>
                            <td class="table__cell fg--1"><?=$livro['edicao']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Idioma</th>
                            <td class="table__cell fg--1"><?=$livro['idioma']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Páginas</th>
                            <td class="table__cell fg--1"><?=$livro['paginas']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Unidades</th>
                            <td class="table__cell fg--1"><?=$livro['unidades']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Registrado em</th>
                            <td class="table__cell fg--1"><?=$livro['registrado_em']?></td>
                        </tr>
                    </tbody>
                </table> 
                <div class="box-heading">
                    <h1 class="box-heading__title">Descrição</h1>
                </div>
                <div class="sample mb--20">
                    <p class="sample__text"><?=$livro['descricao']?></p>
                </div>
                <div class="operation-group mb--20">
                    <button class="btn bg--warning" onclick="window.location.href='<?=$basePath?>/livros/update/<?=$livro['ID']?>';">Editar</button>
                    <button class="btn bg--danger" onclick="window.location.href='<?=$basePath?>/livros/delete/<?=$livro['ID']?>'">Deletar</button>
                </div>
                <div class="operation-group">
                    <button class="btn bg--success" onclick="window.location.href='<?=$basePath?>/livros/entrada/<?=$livro['ID']?>';">Registrar Entrada</button>
                    <button class="btn bg--warning" onclick="window.location.href='<?=$basePath?>/livros/saida/<?=$livro['ID']?>';">Registrar Saída</button>
                </div>
            </div>
        </section>
    </main>
    <?php
    require __DIR__ . '/../partials/footer.php';
    ?>
    <?php
    require __DIR__ . '/../partials/scripts.php';
    ?>
</body>
</html>