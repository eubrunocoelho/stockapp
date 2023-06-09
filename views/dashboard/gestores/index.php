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
                    <form action="<?=$basePath?>/gestores" method="GET">
                        <input type="text" class="search__input" id="search" name="search" placeholder="Pesquisar..."<?php
                        if (
                            !(empty($URI)) &&
                            (isset($URI['search']))
                        ) {
                            echo ' value="' . $URI['search'] . '"';
                        }
                        ?>>
                        <button class="search__btn">
                            <i class="fa-solid fa-magnifying-glass btn__icon"></i>
                        </button>
                    </form>
                </div>
                <div class="filter__order-by">
                    <select class="order-by__select" id="filterBy">
                        <option value="<?=$filterBy['URL']['todos']?>"<?php
                        if (
                            ($status['status']['todos'])
                        ) {
                            echo ' selected';
                        }
                        ?>>Todos</option>
                        <option value="<?=$filterBy['URL']['active']?>"<?php
                        if (
                            ($status['status']['active'])
                        ) {
                            echo ' selected';
                        }
                        ?>>Ativos</option>
                        <option value="<?=$filterBy['URL']['inactive']?>"<?php
                        if (
                            ($status['status']['inactive'])
                        ) {
                            echo ' selected';
                        }
                        ?>>Inativos</option>
                    </select>
                </div>
            </div>
        </section>
        <section class="section container mb--20">
            <?php
            if (
                !(empty($messages)) &&
                (array_key_exists('message.warning', $messages))
            ) {
            ?>
            <div class="alert-box alert--warning mb--20">
                <p class="alert-box__text"><?=$messages['message.warning'][0]?></p>
            </div>
            <?php
            }
            ?>
            <?php
            if (
                !(empty($messages)) &&
                (array_key_exists('message.success', $messages))
            ) {
            ?>
            <div class="alert-box alert--success mb--20">
                <p class="alert-box__text"><?=$messages['message.success'][0]?></p>
            </div>
            <?php
            }
            ?>
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
                            (isset($gestores)) &&
                            !(empty($gestores))
                        ) {
                            foreach ($gestores as $gestor) {
                        ?>
                        <tr class="table__tr">
                            <td class="table__cell tc--w50"><?=$gestor['ID']?></td>
                            <td class="table__cell fg--1">
                                <a href="<?=$basePath?>/gestores/show/<?=$gestor['ID']?>"><?=$gestor['nome']?></a>
                            </td>
                            <td class="table__cell tc--w210"><?=$gestor['cargo']?></td>
                            <?php
                            if (
                                ($gestor['status'] == 'Ativo')
                            ) {
                            ?>
                            <td class="table__cell tc--w210 text--success"><?=$gestor['status']?></td>
                            <?php
                            } elseif (
                                ($gestor['status'] == 'Inativo')
                            ) {
                            ?>
                            <td class="table__cell tc--w210 text--danger"><?=$gestor['status']?></td>
                            <?php
                            }
                            ?>
                            <td class="table__cell tc--w105"><i class="fa-solid fa-marker text--warning action__icon"></i><a href="<?=$basePath?>/gestores/update/<?=$gestor['ID']?>" class="text--warning">Editar</a></td>
                            <?php
                            if (
                                ($gestor['status'] == 'Inativo')
                            ) {
                            ?>
                            <td class="table__cell tc--w105"><i class="fa-solid fa-user-plus text--success action__icon"></i><a href="<?=$basePath?>/gestores/status/active/<?=$gestor['ID']?>" class="text--success">Ativar</a></td>
                            <?php
                            } elseif (
                                ($gestor['status'] == 'Ativo')
                            ) {
                            ?>
                            <td class="table__cell tc--w105"><i class="fa-solid fa-user-minus text--danger action__icon"></i><a href="<?=$basePath?>/gestores/status/inactive/<?=$gestor['ID']?>" class="text--danger">Inativar</a></td>
                            <?php
                            }
                            ?>
                        </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <?php
                if (
                    ($authorize['register'] === true)
                ) {
                ?>
                <button class="btn" onclick="window.location.href='<?=$basePath?>/gestores/register'">Cadastrar Gestor</button>
                <?php
                }
                ?>
            </div>
        </section>
        <section class="section container">
            <div class="pagination">
                <ul class="pagination__list">
                    <li class="pagination__item">
                        <?php
                        if (
                            ($pagination['links']['previous'])
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
                            ($pagination['links']['next'])
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
        let selectFilterBy = document.querySelector('#filterBy');

        selectFilterBy.addEventListener('change', (e) => {
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