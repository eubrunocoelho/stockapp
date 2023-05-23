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
            <h1 class="main-heading__title"><?=$livro['titulo']?></h1>
            <span class="main-heading__breadcrumbs"><a class="breadcrumbs__link" href="<?=$basePath?>/livros/entrada/<?=$livro['ID']?>">Registrar Entrada</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/livros/show/<?=$livro['ID']?>"><?=$livro['titulo']?></a> / <a class="breadcrumbs__link" href="<?=$basePath?>/livros">Livros</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/dashboard">Dashboard</a></span>
        </div>
        <section class="section container mb--30">
            <div class="box">
                <div class="box-heading">
                    <h1 class="box-heading__title">Detalhes</h1>
                </div>
                <table class="table">
                    <tbody>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Título</th>
                            <td class="table__cell fg--1"><?=$livro['titulo']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Unidades</th>
                            <td class="table__cell fg--1"><?=$livro['unidades']?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <section class="section container">
            <div class="box">
                <div class="box-heading">
                    <h1 class="box-heading__title">Registrar Entrada</h1>
                </div>
                <form class="form" action="<?=$basePath?>/livros/entrada/<?=$livro['ID']?>" method="POST">
                    <div class="form__group">
                        <label class="form__label">Unidades:</label>
                        <input type="text" class="form__input" id="unidades" name="unidades" placeholder="Unidades"<?php
                        if (
                            !(empty($persistRegisterValues)) &&
                            (isset($persistRegisterValues['unidades'])) &&
                            ($persistRegisterValues['unidades'] !== null)
                        ) {
                            echo ' value="' . $persistRegisterValues['unidades'] . '"';
                        }
                        ?>>
                    </div>
                    <button type="submit" class="btn mt--20">Registrar</button>
                </form>
                <?php
                if (
                    (isset($errors)) &&
                    !(empty($errors))
                ) {
                    foreach ($errors as $error) {
                ?>
                <div class="alert-box alert--danger mt--20">
                    <p class="alert-box__text"><?=$error?></p>
                </div>
                <?php
                    }
                }
                ?>
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