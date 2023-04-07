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
            <h1 class="main-heading__title">Editar Perfil</h1>
            <span class="main-heading__breadcrumbs"><a class="breadcrumbs__link" href="<?=$basePath?>/profile/update/<?=$gestorProfile['ID']?>">Editar Perfil</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/profile/show/<?=$gestorProfile['ID']?>"><?=$gestorProfile['nome']?></a> / <a class="breadcrumbs__link" href="./gestores.html">Gestores</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/dashboard">Dashboard</a></span>
        </div>
        <section class="section container mb--30">
            <div class="box">
                <div class="box-heading">
                    <h1 class="box-heading__title"><?=$gestorProfile['nome']?></h1>
                </div>
                <table class="table">
                    <tbody>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">Nome</th>
                            <td class="table__cell fg--1"><?=$gestorProfile['nome']?></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">E-mail</th>
                            <td class="table__cell fg--1">eu.brunocoelho94@gmail.com</td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__cell tc--w210">CPF</th>
                            <td class="table__cell fg--1"><?=$gestorProfile['cpf']?></td>
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
            </div>
        </section>
        <section class="section container">
            <div class="box">
                <div class="box-heading">
                    <h1 class="box-heading__title">Editar Perfil</h1>
                </div>
                <form class="form" action="<?=$basePath?>/update/<?=$gestorProfile['ID']?>" method="POST">
                    <div class="form__group">
                        <label class="form__label">Foto de Perfil:</label>
                        <input type="file" id="upload" hidden>
                        <label for="upload" class="form__custom-upload">Escolher arquivo...</label>
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="nome">Nome:</label>
                        <input type="text" class="form__input" id="nome" name="nome" value="Bruno Coelho">
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="email">Email:</label>
                        <input type="text" class="form__input" id="email" name="email" value="Bruno Coelho">
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="cpf">CPF:</label>
                        <input type="text" class="form__input" id="cpf" name="cpf" value="000.000.000-00">
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="telefone">Telefone:</label>
                        <input type="text" class="form__input" id="telefone" name="telefone" value="eu.brunocoelho94@gmail.com">
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="endereco">Endereço:</label>
                        <input type="text" class="form__input" id="endereco" name="endereco" value="Rua XV de novembro">
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="cargo">Cargo:</label>
                        <select class="form__select" id="cargo" name="cargo">
                            <option value="1">Administrador</option>
                            <option value="2">Gestor</option>
                        </select>
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="genero">Gênero:</label>
                        <select class="form__select" id="genero" name="genero">
                            <option value="1">Masculino</option>
                            <option value="2">Feminino</option>
                        </select>
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="status">Status:</label>
                        <select class="form__select" id="status" name="status">
                            <option value="1">Ativo</option>
                            <option value="2">Inativo</option>
                        </select>
                    </div>
                    <button type="submit" class="btn mt--20">Salvar Informações</button>
                </form>
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