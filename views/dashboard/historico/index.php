<?php
dd($historico);
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
            <h1 class="main-heading__title">Histórico de Entrada/Saída</h1>
            <span class="main-heading__breadcrumbs"><a class="breadcrumbs__link" href="<?=$basePath?>/historico">Histórico</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/dashboard">Dashboard</a></span>
        </div>
        <section class="section container mb--30">
            <div class="filter">
                <div class="filter__order-by">
                    <select class="order-by__select">
                        <option>Entrada</option>
                        <option>Saída</option>
                    </select>
                </div>
            </div>
        </section>
        <section class="section container mb--20">
            <div class="box">
                <div class="box-heading">
                    <h1 class="box-heading__title"><?=$list['title']?></h1>
                </div>
                <table class="table">
                    <thead>
                        <tr class="table__tr">
                            <th class="table__cell tc--w50">ID</th>
                            <th class="table__cell fg--1">Nome</th>
                            <th class="table__cell tc--w210"><?=$list['table']['thead']['unidades']?></th>
                            <th class="table__cell tc--w210">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (
                            isset($historico) &&
                            !(empty($historico))
                        ) {
                            foreach ($historico as $historico) {
                        ?>
                        <tr class="table__tr">
                            <td class="table__cell tc--w50">920</td>
                            <td class="table__cell fg--1">
                                <a href="./livro.html">Senhor dos Anéis</a>
                            </td>
                            <td class="table__cell tc--w210">2</td>
                            <td class="table__cell tc--w210">05/03/2023 ás 15:16:01</td>
                        </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
        <section class="section container">
            <div class="pagination">
                <ul class="pagination__list">
                    <li class="pagination__item">
                        <span class="pagination__link text--disabled">«</span>
                    </li>
                    <li class="pagination__item">
                        <a href="./historico.html" class="pagination__link border--active">1</a>
                    </li>
                    <li class="pagination__item">
                        <a href="./historico.html" class="pagination__link">2</a>
                    </li>
                    <li class="pagination__item">
                        <span class="pagination__link text--disabled">...</span>
                    </li> 
                    <li class="pagination__item">
                        <a href="./historico.html" class="pagination__link">10.102</a>
                    </li>
                    <li class="pagination__item">
                        <a href="./historico.html" class="pagination__link">»</a>
                    </li>
                </ul>
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