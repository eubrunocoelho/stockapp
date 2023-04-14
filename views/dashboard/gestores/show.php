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
            <span class="main-heading__breadcrumbs"><a class="breadcrumbs__link" href="<?=$basePath?>/gestores/show/<?=$gestor['ID']?>">Bruno Coelho</a> / <a class="breadcrumbs__link" href="./gestores.html">Gestores</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/dashboard">Dashboard</a></span>
        </div>
        <section class="section container">
            <div class="box">
                <div class="box-heading">
                    <h1 class="box-heading__title"><?=$gestorProfile['nome']?></h1>
                </div>
                <div class="profile mb--20">
                    <div class="profile__avatar"></div>
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
                        <?php
                        if (isset($gestorProfile['telefone'])) {
                        ?>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Telefone</th>
                            <td class="table__cell fg--1"><?=$gestorProfile['telefone']?></td>
                        </tr>
                        <?php
                        }
                        if (isset($gestorProfile['endereco'])) {
                        ?>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Endereço</th>
                            <td class="table__cell fg--1"><?=$gestorProfile['endereco']?></td>
                        </tr>
                        <?php
                        }
                        ?>
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
                if ($authorize['update']['status']) {
                ?>
                <div class="operation-group mb--20">
                    <button class="btn bg--danger" onclick="window.location.href='./gerenciar-status-gestor.html';">Inativar</button>
                </div>
                <?php
                }
                ?>
                <?php
                if ($authorize['update']['profile']) {
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