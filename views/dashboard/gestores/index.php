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
            <h1 class="main-heading__title">Gestores</h1>
            <span class="main-heading__breadcrumbs"><a class="breadcrumbs__link" href="<?=$basePath?>/gestores">Gestores</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/dashboard">Dashboard</a></span>
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
                        <option>Todos</option>
                        <option>Ativos</option>
                        <option>Inativos</option>
                    </select>
                </div>
            </div>
        </section>
        <section class="section container mb--20">
            <div class="box">
                <div class="box-heading">
                    <h1 class="box-heading__title">Lista de Gestores</h1>
                </div>
                <table class="table mb--20">
                    <thead>
                        <tr class="table__tr">
                            <th class="table__cell tc--w50">ID</th>
                            <th class="table__cell fg--1">Nome</th>
                            <th class="table__cell tc--w210">Função</th>
                            <th class="table__cell tc--w210">Status</th>
                            <th class="table__cell tc--w210" colspan="2">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (
                            isset($gestores) &&
                            !(empty($gestores))
                        ) {
                            foreach ($gestores as $gestor) {
                        ?>
                        <tr class="table__tr">
                            <td class="table__cell tc--w50">1</td>
                            <td class="table__cell fg--1">
                                <a href="<?=$basePath?>/gestores/show/<?=$gestor['ID']?>"><?=$gestor['nome']?></a>
                            </td>
                            <td class="table__cell tc--w210"><?=$gestor['cargo']?></td>
                            <?php
                            if ($gestor['status'] == 'Ativo') {
                            ?>
                            <td class="table__cell tc--w210 text--success"><?=$gestor['status']?></td>
                            <?php
                            } elseif ($gestor['status'] == 'Inativo') {
                            ?>
                            <td class="table__cell tc--w210 text--danger"><?=$gestor['status']?></td>
                            <?php
                            }
                            ?>
                            <td class="table__cell tc--w105"><i class="fa-solid fa-marker text--warning action__icon"></i><a href="<?=$basePath?>/gestores/update/<?=$gestor['ID']?>" class="text--warning">Editar</a></td>
                            <td class="table__cell tc--w105"><i class="fa-solid fa-user-minus text--danger action__icon"></i><a href="<?=$basePath?>/gestores/update/status/<?=$gestor['ID']?>" class="text--danger">Inativar</a></td>
                        </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <button class="btn" onclick="window.location.href='<?=$basePath?>/gestores/register'">Cadastrar Gestor</button>
            </div>
        </section>
        <section class="section container">
            <div class="pagination">
                <ul class="pagination__list">
                    <li class="pagination__item">
                        <span class="pagination__link text--disabled">«</span>
                    </li>
                    <li class="pagination__item">
                        <a href="./gestores.html" class="pagination__link border--active">1</a>
                    </li>
                    <li class="pagination__item">
                        <span class="pagination__link text--disabled">»</span>
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