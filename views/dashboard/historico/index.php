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
                    <select class="order-by__select" id="listBy">
                        <option value="<?=$listBy['URL']['entrada']?>"<?php
                        if (
                            ($status['listBy']['entrada'])
                        ) {
                            echo ' selected';
                        }
                        ?>>Entrada</option>
                        <option value="<?=$listBy['URL']['saida']?>"<?php
                        if (
                            ($status['listBy']['saida'])
                        ) {
                            echo ' selected';
                        }
                        ?>>Saída</option>
                    </select>
                </div>
            </div>
        </section>
        <section class="section container mb--20">
            <div class="box">
                <div class="box-heading">
                    <h1 class="box-heading__title"><?=$index['title']?></h1>
                </div>
                <table class="table">
                    <thead>
                        <tr class="table__tr">
                            <th class="table__cell tc--w50">ID</th>
                            <th class="table__cell fg--1">Título</th>
                            <th class="table__cell tc--w210"><?=$index['table']['thead']['unidades']?></th>
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
                            <td class="table__cell tc--w50"><?=$historico['ID']?></td>
                            <td class="table__cell fg--1">
                                <a href="<?=$basePath?>/livros/show/<?=$historico['ID_livro']?>"><?=$historico['titulo']?></a>
                            </td>
                            <td class="table__cell tc--w210"><?=$historico['quantidade']?></td>
                            <td class="table__cell tc--w210"><?=$historico['registrado_em']?></td>
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
                        <?php
                        if (
                            $pagination['links']['previous']
                        ) {
                        ?>
                        <a href="<?=$pagination['URL']['previous']?>" class="pagination__link">«</a>
                        <?php
                        } else {
                        ?>
                        <span class="pagination__link text--disabled">«</span>
                        <?php
                        }
                        ?>
                    </li>
                    <li class="pagination__item">
                        <a href="<?=$pagination['URL']['current']?>" class="pagination__link border--active"><?=$pagination['currentPage']?></a>
                    </li>
                    <li class="pagination__item">
                        <?php
                        if (
                            $pagination['links']['next']
                        ) {
                        ?>
                        <a href="<?=$pagination['URL']['next']?>" class="pagination__link">»</a>
                        <?php
                        } else {
                        ?>
                        <span class="pagination__link text--disabled">»</span>
                        <?php
                        }
                        ?>
                    </li>
                </ul>
            </div>
        </section>
    </main>
    <?php
    require __DIR__ . '/../partials/footer.php';
    ?>
    <script>
        let selectListBy = document.querySelector('#listBy');

        selectListBy.addEventListener('change', (e) => {
            let goToURL = e.currentTarget.value;

            redirect(goToURL);
        });

        let redirect = (goToURL) => {
            window.location = goToURL;
        };
    </script>
    <?php
    require __DIR__ . '/../partials/scripts.php';
    ?>
</body>
</html>