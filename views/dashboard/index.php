<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php
    require __DIR__ . '/partials/head.php';
    ?>
</head>
<body>
    <div class="window-overlay">
        <?php
        require __DIR__ . '/partials/side-navigation.php';
        ?>
    </div>
    <?php
    require __DIR__ . '/partials/header.php';
    ?>
    <main class="main">
        <div class="main-heading container">
            <h1 class="main-heading__title">Dashboard</h1>
            <span class="main-heading__breadcrumbs"><a class="breadcrumbs__link" href="./index.html">Dashboard</a> / <a class="breadcrumbs__link" href="./index.html">Dashboard</a></span>
        </div>
        <section class="section container mb--30">
            <div class="cards">
                <div class="card">
                    <div class="card__summary">
                        <span class="summary__description">Livros</span>
                        <span class="summary__number">41.597</span>
                    </div>
                    <div class="card__ellipse">
                        <i class="fa-solid fa-layer-group ellipse__icon"></i>
                    </div>
                </div>
                <div class="card">
                    <div class="card__summary">
                        <span class="summary__description">Entradas</span>
                        <span class="summary__number">102.441</span>
                    </div>
                    <div class="card__ellipse">
                        <i class="fa-solid fa-arrow-down-short-wide ellipse__icon"></i>
                    </div>
                </div>
                <div class="card">
                    <div class="card__summary">
                        <span class="summary__description">Saídas</span>
                        <span class="summary__number">27.123</span>
                    </div>
                    <div class="card__ellipse">
                        <i class="fa-solid fa-arrow-up-short-wide ellipse__icon"></i>
                    </div>
                </div>
            </div>
        </section>
        <section class="section container">
            <div class="box">
                <div class="box-heading">
                    <h1 class="box-heading__title">Recém Adicionados</h1>
                </div>
                <table class="table">
                    <thead>
                        <tr class="table__tr">
                            <th class="table__cell fg--1">Livro</th>
                            <th class="table__cell tc--w210">Editora</th>
                            <th class="table__cell tc--w210">Adicionado Em</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table__tr">
                            <td class="table__cell fg--1">
                                <a href="./livro.html">O Senhor dos Anéis</a>
                            </td>
                            <td class="table__cell tc--w210">Allen & Unwin</td>
                            <td class="table__cell tc--w210">03/03/2023 ás 14:58:01</td>
                        </tr>
                        <tr class="table__tr">
                            <td class="table__cell fg--1">
                                <a href="./livro.html">365 Dias de Inteligência</a>
                            </td>
                            <td class="table__cell tc--w210">Dreamsellers</td>
                            <td class="table__cell tc--w210">03/03/2023 ás 10:08:22</td>
                        </tr>
                        <tr class="table__tr">
                            <td class="table__cell fg--1">
                                <a href="./livro.html">A Mandíbula de Caim</a>
                            </td>
                            <td class="table__cell tc--w210">Intrínseca</td>
                            <td class="table__cell tc--w210">03/03/2023 ás 09:05:59</td>
                        </tr>
                        <tr class="table__tr">
                            <td class="table__cell fg--1">
                                <a href="./livro.html">Antes Que o Café Esfrie</a>
                            </td>
                            <td class="table__cell tc--w210">Editora Valentina</td>
                            <td class="table__cell tc--w210">03/03/2023 ás 09:01:28</td>
                        </tr>
                        <tr class="table__tr">
                            <td class="table__cell fg--1">
                                <a href="./livro.html">O Senhor dos Anéis</a>
                            </td>
                            <td class="table__cell tc--w210">Allen & Unwin</td>
                            <td class="table__cell tc--w210">03/03/2023 ás 14:58:01</td>
                        </tr>
                        <tr class="table__tr">
                            <td class="table__cell fg--1">
                                <a href="./livro.html">365 Dias de Inteligência</a>
                            </td>
                            <td class="table__cell tc--w210">Dreamsellers</td>
                            <td class="table__cell tc--w210">03/03/2023 ás 10:08:22</td>
                        </tr>
                        <tr class="table__tr">
                            <td class="table__cell fg--1">
                                <a href="./livro.html">A Mandíbula de Caim</a>
                            </td>
                            <td class="table__cell tc--w210">Intrínseca</td>
                            <td class="table__cell tc--w210">03/03/2023 ás 09:05:59</td>
                        </tr>
                        <tr class="table__tr">
                            <td class="table__cell fg--1">
                                <a href="./livro.html">Antes Que o Café Esfrie</a>
                            </td>
                            <td class="table__cell tc--w210">Editora Valentina</td>
                            <td class="table__cell tc--w210">03/03/2023 ás 09:01:28</td>
                        </tr>
                        <tr class="table__tr">
                            <td class="table__cell fg--1">
                                <a href="./livro.html">O Senhor dos Anéis</a>
                            </td>
                            <td class="table__cell tc--w210">Allen & Unwin</td>
                            <td class="table__cell tc--w210">03/03/2023 ás 14:58:01</td>
                        </tr>
                        <tr class="table__tr">
                            <td class="table__cell fg--1">
                                <a href="./livro.html">365 Dias de Inteligência</a>
                            </td>
                            <td class="table__cell tc--w210">Dreamsellers</td>
                            <td class="table__cell tc--w210">03/03/2023 ás 10:08:22</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <?php
    require __DIR__ . '/partials/footer.php';
    ?>
    <?php
    require __DIR__ . '/partials/scripts.php';
    ?>
</body>
</html>