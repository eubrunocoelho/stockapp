<?php
// dd($livro, true);
?>
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
            <h1 class="main-heading__title">Editar Livro</h1>
            <span class="main-heading__breadcrumbs"><a class="breadcrumbs__link" href="<?=$basePath?>/livros/update/<?=$livro['ID']?>">Editar Livro</a> / <a class="breadcrumbs__link" href="#">O Senhor dos Anéis</a> / <a class="breadcrumbs__link" href="#">Livros</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/dashboard">Dashboard</a></span>
        </div>
        <section class="section container mb--30">
            <div class="box">
                <div class="box-heading">
                    <h1 class="box-heading__title"><?=$livro['titulo']?></h1>
                </div>
                <table class="table">
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
                    </tbody>
                </table>
            </div>
        </section>
        <section class="section container">
            <div class="box">
                <div class="box-heading">
                    <h1 class="box-heading__title">Editar Livro</h1>
                </div>
                <form class="form" action="<?=$basePath?>/livros/update/<?=$livro['ID']?>" method="POST">
                    <div class="form__group">
                        <label class="form__label">Título: <span class="text--danger">*</span></label>
                        <input type="text" class="form__input" id="titulo" name="titulo" placeholder="Ex.: O Senhor dos Anéis"<?php
                        if (
                            !(empty($persistUpdateValues)) &&
                            (isset($persistUpdateValues['titulo'])) &&
                            ($persistUpdateValues['titulo'] !== null)
                        ) {
                            echo ' value="' . $persistUpdateValues['titulo'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Autor(es): <span class="text--danger">*</span></label>
                        <input type="text" class="form__input" id="autor" name="autor" placeholder="Ex.: João Augusto, José Antônio"<?php
                        if (
                            !(empty($persistUpdateValues)) &&
                            (isset($persistUpdateValues['autor'])) &&
                            ($persistUpdateValues['titulo'] !== null)
                        ) {
                            echo ' value="' . $persistUpdateValues['autor'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Editora(s): <span class="text--danger">*</span></label>
                        <input type="text" class="form__input" id="editora" name="editora" placeholder="Ex.: Globo Livros, Companhias das Letras"<?php
                        if (
                            !(empty($persistUpdateValues)) &&
                            (isset($persistUpdateValues['editora'])) &&
                            ($persistUpdateValues['editora'] !== null)
                        ) {
                            echo ' value="' . $persistUpdateValues['editora'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Formato:</label>
                        <input type="text" class="form__input" id="formato" name="formato" placeholder="Ex.: PDF"<?php
                        if (
                            !(empty($persistUpdateValues)) &&
                            (isset($persistUpdateValues['formato'])) &&
                            ($persistUpdateValues['formato'] !== null)
                        ) {
                            echo ' value="' . $persistUpdateValues['formato'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Ano da Publicação:</label>
                        <input type="text" class="form__input" id="ano_publicacao" name="ano_publicacao" placeholder="Ex.: 2012"<?php
                        if (
                            !(empty($persistUpdateValues)) &&
                            (isset($persistUpdateValues['ano_publicacao'])) &&
                            ($persistUpdateValues['ano_publicacao'] !== null)
                        ) {
                            echo ' value="' . $persistUpdateValues['ano_publicacao'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">ISBN:</label>
                        <input type="text" class="form__input" id="isbn" name="isbn" placeholder="Ex.: 0000000000000"<?php
                        if (
                            !(empty($persistUpdateValues)) &&
                            (isset($persistUpdateValues['isbn'])) &&
                            ($persistUpdateValues['isbn'] !== null)
                        ) {
                            echo ' value="' . $persistUpdateValues['isbn'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Edição:</label>
                        <input type="text" class="form__input" id="edicao" name="edicao" placeholder="Ex.: 3"<?php
                        if (
                            !(empty($persistUpdateValues)) &&
                            (isset($persistUpdateValues['edicao'])) &&
                            ($persistUpdateValues['edicao'] !== null)
                        ) {
                            echo ' value="' . $persistUpdateValues['edicao'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Idioma: <span class="text--danger">*</span></label>
                        <input type="text" class="form__input" id="idioma" name="idioma" placeholder="Ex.: Português"<?php
                        if (
                            !(empty($persistUpdateValues)) &&
                            (isset($persistUpdateValues['idioma'])) &&
                            ($persistUpdateValues['idioma'] !== null)
                        ) {
                            echo ' value="' . $persistUpdateValues['idioma'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Páginas:</label>
                        <input type="text" class="form__input" id="paginas" name="paginas" placeholder="Ex.: 1200"<?php
                        if (
                            !(empty($persistUpdateValues)) &&
                            (isset($persistUpdateValues['paginas'])) &&
                            ($persistUpdateValues['paginas'] !== null)
                        ) {
                            echo ' value="' . $persistUpdateValues['paginas'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group ai--start">
                        <label class="form__label pt--12">Descrição:</label>
                        <textarea class="form__textarea" id="descricao" name="descricao" placeholder="Ex.: A batalha do anel começou na Terra Média..."><?php
                        if (
                            !(empty($persistUpdateValues)) &&
                            (isset($persistUpdateValues['descricao'])) &&
                            ($persistUpdateValues['descricao'] !== null)
                        ) {
                            echo $persistUpdateValues['descricao'];
                        }
                        ?></textarea>
                    </div>
                    <button type="submit" class="btn mt--20">Editar</button>
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