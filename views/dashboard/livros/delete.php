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
            <h1 class="main-heading__title">Ativar</h1>
            <span class="main-heading__breadcrumbs"><a class="breadcrumbs__link" href="<?=$basePath?>/livros/delete/<?=$livro['ID']?>">Deletar</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/livros/show/<?=$livro['ID']?>"><?=$livro['titulo']?></a> / <a class="breadcrumbs__link" href="<?=$basePath?>/livros">Livros</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/dashboard">Dashboard</a></span>
        </div>
        <section class="section container">
            <div class="box">
                <div class="box-heading">
                    <h1 class="box-heading__title"><?=$livro['titulo']?></h1>
                </div>
                <div class="box-heading">
                    <h1 class="box-heading__title">Informações do Livro</h1>
                </div>
                <table class="table mb--20">
                    <tbody>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Titulo</th>
                            <td class="table__cell fg--1"><?=$livro['titulo']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Unidades</th>
                            <td class="table__cell fg--1"><?=$livro['unidades']?></td>
                        </tr>
                    </tbody>
                </table>
                <div class="operation-group mb--20">
                    <button class="btn bg--danger" onclick="window.location.href='<?=$basePath?>/livros/delete/<?=$livro['ID']?>?confirm=delete';">Deletar</button>
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