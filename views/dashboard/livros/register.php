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
            <h1 class="main-heading__title">Cadastrar Livro</h1>
            <span class="main-heading__breadcrumbs"><a class="breadcrumbs__link" href="<?=$basePath?>/livros/register">Cadastrar Livro</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/livros">Livros</a> / <a class="breadcrumbs__link" href="<?=$basePath?>">Dashboard</a></span>
        </div>
        <section class="section container">
            <div class="box">
                <div class="box-heading">
                    <h1 class="box-heading__title">Cadastrar Livro</h1>
                </div>
                <form class="form" action="<?=$basePath?>/livros/register" method="POST">
                    <div class="form__group">
                        <label class="form__label">Título: <span class="text--danger">*</span></label>
                        <input type="text" class="form__input" id="titulo" name="titulo" placeholder="Ex.: O Senhor dos Anéis"<?php
                        if (
                            !(empty($persistRegisterValues)) &&
                            (isset($persistRegisterValues['titulo'])) &&
                            ($persistRegisterValues['titulo'] !== null)
                        ) {
                            echo ' value="' . $persistRegisterValues['titulo'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Autor(es): <span class="text--danger">*</span></label>
                        <input type="text" class="form__input" id="autor" name="autor" placeholder="Ex.: João Augusto, José Antônio"<?php
                        if (
                            !(empty($persistRegisterValues)) &&
                            (isset($persistRegisterValues['autor'])) &&
                            ($persistRegisterValues['autor'] !== null)
                        ) {
                            echo ' value="' . $persistRegisterValues['autor'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Editora(s): <span class="text--danger">*</span></label>
                        <input type="text" class="form__input" id="editora" name="editora" placeholder="Ex.: Globo Livros, Companhia da Letras"<?php
                        if (
                            !(empty($persistRegisterValues)) &&
                            (isset($persistRegisterValues['editora'])) &&
                            ($persistRegisterValues['editora'] !== null)
                        ) {
                            echo ' value="' . $persistRegisterValues['editora'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Formato:</label>
                        <input type="text" class="form__input" id="formato" name="formato" placeholder="Ex.: PDF"<?php
                        if (
                            !(empty($persistRegisterValues)) &&
                            (isset($persistRegisterValues['formato'])) &&
                            ($persistRegisterValues['formato'] !== null)
                        ) {
                            echo ' value="' . $persistRegisterValues['formato'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Ano da Publicação: <span class="text--danger">*</span></label>
                        <input type="text" class="form__input" id="ano_publicacao" name="ano_publicacao" placeholder="Ex.: 2012"<?php
                        if (
                            !(empty($persistRegisterValues)) &&
                            (isset($persistRegisterValues['ano_publicacao'])) &&
                            ($persistRegisterValues['ano_publicacao'] !== null)
                        ) {
                            echo ' value="' . $persistRegisterValues['ano_publicacao'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">ISBN:</label>
                        <input type="text" class="form__input" id="isbn" name="isbn" placeholder="Ex.: 0000000000000"<?php
                        if (
                            !(empty($persistRegisterValues)) &&
                            (isset($persistRegisterValues['isbn'])) &&
                            ($persistRegisterValues['isbn'] !== null)
                        ) {
                            echo ' value="' . $persistRegisterValues['isbn'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Edição:</label>
                        <input type="text" class="form__input" id="edicao" name="edicao" placeholder="Ex.: 3"<?php
                        if (
                            !(empty($persistRegisterValues)) &&
                            (isset($persistRegisterValues['edicao'])) &&
                            ($persistRegisterValues['edicao'] !== null)
                        ) {
                            echo ' value="' . $persistRegisterValues['edicao'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Idioma: <span class="text--danger">*</span></label>
                        <input type="text" class="form__input" id="idioma" name="idioma" placeholder="Ex.: Português"<?php
                        if (
                            !(empty($persistRegisterValues)) &&
                            (isset($persistRegisterValues['idioma'])) &&
                            ($persistRegisterValues['idioma'] !== null)
                        ) {
                            echo ' value="' . $persistRegisterValues['idioma'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Páginas:</label>
                        <input type="text" class="form__input" id="paginas" name="paginas" placeholder="Ex.: 1200"<?php
                        if (
                            !(empty($persistRegisterValues)) &&
                            (isset($persistRegisterValues['paginas'])) &&
                            ($persistRegisterValues['paginas'] !== null)
                        ) {
                            echo ' value="' . $persistRegisterValues['paginas'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group ai--start">
                        <label class="form__label pt--12">Descrição:</label>
                        <textarea class="form__textarea" id="descricao" name="descricao" placeholder="Ex.: A batalha do anel começou na Terra Média..."><?php
                        if (
                            !(empty($persistRegisterValues)) &&
                            (isset($persistRegisterValues['descricao'])) &&
                            ($persistRegisterValues['descricao'] !== null)
                        ) {
                            echo $persistRegisterValues['descricao'];
                        }
                        ?></textarea>
                    </div>
                    <button type="submit" class="btn mt--20">Cadastrar</button>
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