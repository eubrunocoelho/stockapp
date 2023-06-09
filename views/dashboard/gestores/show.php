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
            <h1 class="main-heading__title"><?=$gestorProfile['nome']?></h1>
            <span class="main-heading__breadcrumbs"><a class="breadcrumbs__link" href="<?=$basePath?>/gestores/show/<?=$gestor['ID']?>">Bruno Coelho</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/gestores">Gestores</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/dashboard">Dashboard</a></span>
        </div>
        <section class="section container">
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
                    <h1 class="box-heading__title"><?=$gestorProfile['nome']?></h1>
                </div>
                <div class="profile mb--20">
                    <div class="profile__avatar">
                        <?php
                        if (
                            (
                                ($gestorProfile['img_url'] == '') ||
                                ($gestorProfile['img_url'] == null)
                            )
                        ) {
                        ?>
                        <img src="<?=$basePath?>/uploads/img/profile/default.jpg" class="profile__img">
                        <?php
                        } else {
                        ?>
                        <img src="<?=$basePath?>/uploads/img/profile/<?=$gestorProfile['img_url']?>" class="profile__img">
                        <?php
                        }
                        ?>
                    </div>
                    <div class="profile__description">
                        <h1 class="description__name"><?=$gestorProfile['nome']?></h1>
                        <span class="description__email"><?=$gestorProfile['email']?></span>
                    </div>
                </div>
                <div class="box-heading">
                    <h1 class="box-heading__title">Informações Pessoais</h1>
                </div>
                <table class="table mb--20">
                    <tbody>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Nome</th>
                            <td class="table__cell fg--1"><?=$gestorProfile['nome']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">CPF</th>
                            <td class="table__cell fg--1"><?=$gestorProfile['cpf']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">E-mail</th>
                            <td class="table__cell fg--1"><?=$gestorProfile['email']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Função</th>
                            <td class="table__cell fg--1"><?=$gestorProfile['nome']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Telefone</th>
                            <td class="table__cell fg--1"><?=$gestorProfile['telefone']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Endereço</th>
                            <td class="table__cell fg--1"><?=$gestorProfile['endereco']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Cargo</th>
                            <td class="table__cell fg--1"><?=$gestorProfile['cargo']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Gênero</th>
                            <td class="table__cell fg--1"><?=$gestorProfile['genero']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Status</th>
                            <td class="table__cell fg--1"><?=$gestorProfile['status']?></td>
                        </tr>
                    </tbody>
                </table>
                <?php
                if (
                    ($authorize['update']['status'])
                ) {
                ?>
                <div class="operation-group mb--20">
                    <?php
                    if (
                        ($gestorProfile['status'] == 'Inativo')
                    ) {
                    ?>
                    <button class="btn bg--success" onclick="window.location.href='<?=$basePath?>/gestores/status/active/<?=$gestorProfile['ID']?>';">Ativar</button>
                    <?php
                    } elseif (
                        ($gestorProfile['status'] == 'Ativo')
                    ) {
                    ?>
                    <button class="btn bg--danger" onclick="window.location.href='<?=$basePath?>/gestores/status/inactive/<?=$gestorProfile['ID']?>';">Inativar</button>
                    <?php
                    }
                    ?>
                </div>
                <?php
                }
                ?>
                <?php
                if (
                    ($authorize['update']['profile'])
                ) {
                ?>
                <div class="operation-group">
                    <button class="btn bg--warning" onclick="window.location.href='<?=$basePath?>/gestores/update/<?=$gestorProfile['ID']?>';">Editar informações</button>
                </div>
                <?php
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