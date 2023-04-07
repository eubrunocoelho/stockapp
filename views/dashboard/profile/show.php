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
            <h1 class="main-heading__title">Bruno Coelho</h1>
            <span class="main-heading__breadcrumbs"><a class="breadcrumbs__link" href="<?=$basePath?>/profile/show/<?=$gestor['ID']?>">Bruno Coelho</a> / <a class="breadcrumbs__link" href="./gestores.html">Gestores</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/dashboard">Dashboard</a></span>
        </div>
        <section class="section container">
            <div class="box">
                <div class="box-heading">
                    <h1 class="box-heading__title">Bruno Coelho</h1>
                </div>
                <div class="profile mb--20">
                    <div class="profile__avatar"></div>
                    <div class="profile__description">
                        <h1 class="description__name">Bruno Coelho</h1>
                        <span class="description__email">eu.brunocoelho94@gmail.com</span>
                    </div>
                </div>
                <div class="box-heading">
                    <h1 class="box-heading__title">Informações Pessoais</h1>
                </div>
                <table class="table mb--20">
                    <tbody>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Nome</th>
                            <td class="table__cell fg--1">Bruno Coelho</td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">CPF</th>
                            <td class="table__cell fg--1">000.000.000-00</td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">E-mail</th>
                            <td class="table__cell fg--1">eu.brunocoelho94@gmail.com</td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Função</th>
                            <td class="table__cell fg--1">Administrador</td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Telefone</th>
                            <td class="table__cell fg--1">(66) 4002-8922</td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Gênero</th>
                            <td class="table__cell fg--1">Mascúlino</td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Status</th>
                            <td class="table__cell fg--1">Ativo</td>
                        </tr>
                    </tbody>
                </table>
                <div class="operation-group mb--20">
                    <button class="btn bg--danger" onclick="window.location.href='./gerenciar-status-gestor.html';">Inativar</button>
                </div>
                <div class="operation-group">
                    <button class="btn bg--warning" onclick="window.location.href='./editar-perfil.html';">Editar informações</button>
                </div>
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