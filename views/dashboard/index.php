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
            <span class="main-heading__breadcrumbs"><a class="breadcrumbs__link" href="<?=$basePath?>/dashboard">Dashboard</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/dashboard">Dashboard</a></span>
        </div>
        <section class="section container mb--30">
            <div class="cards">
                <div class="card">
                    <div class="card__summary">
                        <span class="summary__description">Livros</span>
                        <span class="summary__number"><?=$totalLivros?></span>
                    </div>
                    <div class="card__ellipse">
                        <i class="fa-solid fa-layer-group ellipse__icon"></i>
                    </div>
                </div>
                <div class="card">
                    <div class="card__summary">
                        <span class="summary__description">Entradas</span>
                        <span class="summary__number"><?=$totalEntradas?></span>
                    </div>
                    <div class="card__ellipse">
                        <i class="fa-solid fa-arrow-down-short-wide ellipse__icon"></i>
                    </div>
                </div>
                <div class="card">
                    <div class="card__summary">
                        <span class="summary__description">Saídas</span>
                        <span class="summary__number"><?=$totalSaidas?></span>
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
                        <?php
                        if (
                            isset($livros) &&
                            !(empty($livros))
                        ) {
                            foreach ($livros as $livro) {
                        ?>
                        <tr class="table__tr">
                            <td class="table__cell fg--1">
                                <a href="<?=$basePath?>/livros/show/<?=$livro['ID']?>"><?=$livro['titulo']?></a>
                            </td>
                            <td class="table__cell tc--w210"><?=$livro['editora']?>/td>
                            <td class="table__cell tc--w210"><?=$livro['registrado_em']?></td>
                        </tr>
                        <?php
                            }
                        }
                        ?>
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