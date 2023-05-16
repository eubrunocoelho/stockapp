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
            <h1 class="main-heading__title">Livros</h1>
            <span class="main-heading__breadcrumbs"><a class="breadcrumbs__link" href="<?=$basePath?>/livros">Livros</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/dashboard">Dashboard</a></span>
        </div>
        <section class="section container mb--30">
            <div class="filter">
                <div class="filter__search fg--1">
                    <form action="#">
                        <input type="text" class="search__input" placeholder="Pesquisar...">
                        <button class="search__btn">
                            <i class="fa-solid fa-magnifying-glass btn__icon"></i>
                        </button>
                    </form>
                </div>
                <div class="filter__order-by">
                    <select class="order-by__select">
                        <option>a-Z</option>
                        <option>Recentes</option>
                        <option>Entradas</option>
                        <option>Saídas</option>
                    </select>
                </div>
            </div>
        </section>
        <section class="section container mb--20">
            <div class="box">
                <div class="box-heading">
                    <h1 class="box-heading__title">Lista de Livros</h1>
                </div>
                <table class="table mb--20">
                    <thead>
                        <tr class="table__tr">
                            <th class="table__cell tc--w50">ID</th>
                            <th class="table__cell fg--1">Nome</th>
                            <th class="table__cell tc--w210">Unidades</th>
                            <th class="table__cell tc--w210">Editora</th>
                            <th class="table__cell tc--w210" colspan="2">Ações</th>
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
                            <td class="table__cell tc--w50"><?=$livro['ID']?></td>
                            <td class="table__cell fg--1">
                                <a href="<?=$basePath?>/livros/show/<?=$livro['ID']?>"><?=$livro['titulo']?></a>
                            </td>
                            <td class="table__cell tc--w210"><?=$livro['unidades']?></td>
                            <td class="table__cell tc--w210"><?=$livro['editora']?></td>
                            <td class="table__cell tc--w105"><i class="fa-solid fa-marker text--warning action__icon"></i><a href="<?=$basePath?>/livros/update/<?=$livro['ID']?>" class="text--warning">Editar</a></td>
                            <td class="table__cell tc--w105"><i class="fa-solid fa-trash text--danger action__icon"></i><a href="./deletar-livro.html" class="text--danger">Deletar</a></td>
                        </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <button class="btn" onclick="window.location.href='<?=$basePath?>/livros/register';">Cadastrar Livro</button>
            </div>
        </section>
        <section class="section container">
            <div class="pagination">
                <ul class="pagination__list">
                    <li class="pagination__item">
                        <span class="pagination__link text--disabled">«</span>
                    </li>
                    <li class="pagination__item">
                        <a href="./livros.html" class="pagination__link border--active">1</a>
                    </li>
                    <li class="pagination__item">
                        <a href="./livros.html" class="pagination__link">2</a>
                    </li>
                    <li class="pagination__item">
                        <span class="pagination__link text--disabled">...</span>
                    </li> 
                    <li class="pagination__item">
                        <a href="./livros.html" class="pagination__link">901</a>
                    </li>
                    <li class="pagination__item">
                        <a href="./livros.html" class="pagination__link">»</a>
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