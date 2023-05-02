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
            <span class="main-heading__breadcrumbs"><a class="breadcrumbs__link" href="./cadastrar-livro.html">Cadastrar Livro</a> / <a class="breadcrumbs__link" href="./index.html">Dashboard</a></span>
        </div>
        <section class="section container">
            <div class="box">
                <div class="box-heading">
                    <h1 class="box-heading__title">Cadastrar Livro</h1>
                </div>
                <form class="form">
                    <div class="form__group">
                        <label class="form__label">Título: <span class="text--danger">*</span></label>
                        <input type="text" class="form__input" placeholder="Ex.: O Senhor dos Anéis">
                    </div>
                    <div class="form__group">
                        <label class="form__label">Formato:</label>
                        <input type="text" class="form__input" placeholder="Ex.: PDF">
                    </div>
                    <div class="form__group">
                        <label class="form__label">Ano da Publicação: <span class="text--danger">*</span></label>
                        <input type="text" class="form__input" placeholder="Ex.: 2012">
                    </div>
                    <div class="form__group">
                        <label class="form__label">ISBN: <span class="text--danger">*</span></label>
                        <input type="number" class="form__input" placeholder="Ex.: 0000000000000">
                    </div>
                    <div class="form__group">
                        <label class="form__label">Edição:</label>
                        <input type="number" class="form__input" placeholder="Ex.: 3">
                    </div>
                    <div class="form__group">
                        <label class="form__label">Idioma: <span class="text--danger">*</span></label>
                        <input type="text" class="form__input" placeholder="Ex.: Português">
                    </div>
                    <div class="form__group">
                        <label class="form__label">Páginas:</label>
                        <input type="number" class="form__input" placeholder="Ex.: 1200">
                    </div>
                    <div class="form__group ai--start">
                        <label class="form__label pt--12">Descrição:</label>
                        <textarea class="form__textarea" placeholder="Ex.: A batalha do anel começou na Terra Média..."></textarea>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Unidades: <span class="text--danger">*</span></label>
                        <input type="text" class="form__input" placeholder="Ex.: 100">
                    </div>
                    <button type="submit" class="btn mt--20">Cadastrar</button>
                </form>
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