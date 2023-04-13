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
            <span class="main-heading__breadcrumbs"><a class="breadcrumbs__link" href="<?=$basePath?>/gestores/update/<?=$gestorProfile['ID']?>">Editar Perfil</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/gestores/show/<?=$gestorProfile['ID']?>"><?=$gestorProfile['nome']?></a> / <a class="breadcrumbs__link" href="./gestores.html">Gestores</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/dashboard">Dashboard</a></span>
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
                            <td class="table__cell fg--1"><?=$gestorProfile['email']?></td>
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
                <form class="form" action="<?=$basePath?>/gestores/update/<?=$gestorProfile['ID']?>" method="POST">
                    <div class="form__group">
                        <label class="form__label">Foto de Perfil:</label>
                        <input type="file" id="upload" hidden>
                        <label for="upload" class="form__custom-upload">Escolher arquivo...</label>
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="nome">Nome:</label>
                        <input type="text" class="form__input" id="nome" name="nome"<?php
                            if ($persistUpdateValues['nome'] != '' && $persistUpdateValues['nome'] != null) {
                                echo ' value="' . $persistUpdateValues['nome'] . '"';
                            } else {
                                echo ' placeholder="Nome Completo"';
                            }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="email">Email:</label>
                        <input type="text" class="form__input" id="email" name="email"<?php
                            if ($persistUpdateValues['email'] != '' && $persistUpdateValues['email'] != null) {
                                echo ' value="' . $persistUpdateValues['email'] . '"';
                            } else {
                                echo ' placeholder="email@example.com"';
                            }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="cpf">CPF:</label>
                        <input type="text" class="form__input" id="cpf" name="cpf"<?php
                            if ($persistUpdateValues['cpf'] != '' && $persistUpdateValues['cpf'] != null) {
                                echo ' value="' . $persistUpdateValues['cpf'] . '"';
                            } else {
                                echo ' placeholder="000.000.000-00"';
                            }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="telefone">Telefone:</label>
                        <input type="text" class="form__input" id="telefone" name="telefone"<?php
                            if ($persistUpdateValues['telefone'] != '' && $persistUpdateValues['telefone'] != null) {
                                echo ' value="' . $persistUpdateValues['telefone'] . '"';
                            } else {
                                echo '(00) 0000-0000';
                            }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="endereco">Endereço:</label>
                        <input type="text" class="form__input" id="endereco" name="endereco"<?php
                            if ($persistUpdateValues['endereco'] != '' && $persistUpdateValues['endereco'] != null) {
                                echo ' value="' . $persistUpdateValues['endereco'] . '"';
                            } else {
                                echo ' placeholder="Rua Exemplo, 666"';
                            }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="cargo">Cargo:</label>
                        <select class="form__select" id="cargo" name="cargo">
                            <option value="1"<?php
                                if ($persistUpdateValues['cargo'] == 1) {
                                    echo ' selected';
                                }
                            ?>>Administrador</option>
                            <option value="2"<?php
                                if ($persistUpdateValues['cargo'] == 2) {
                                    echo ' selected';
                                }
                            ?>>Gestor</option>
                        </select>
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="genero">Gênero:</label>
                        <select class="form__select" id="genero" name="genero">
                            <option value="1"<?php
                                if ($persistUpdateValues['genero'] == 1) {
                                    echo ' selected';
                                }
                            ?>>Masculino</option>
                            <option value="2"<?php
                                if ($persistUpdateValues['genero'] == 2) {
                                    echo ' selected';
                                }
                            ?>>Feminino</option>
                        </select>
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="status">Status:</label>
                        <select class="form__select" id="status" name="status">
                            <option value="1"<?php
                                if ($persistUpdateValues['status'] == 1) {
                                    echo ' selected';
                                }
                            ?>>Ativo</option>
                            <option value="2"<?php
                                if ($persistUpdateValues['status'] == 2) {
                                    echo ' selected';
                                } 
                            ?>>Inativo</option>
                        </select>
                    </div>
                    <button type="submit" class="btn mt--20">Salvar Informações</button>
                </form>
                <?php
                    if (
                        isset($errors) &&
                        !empty($errors)
                    ) {
                        foreach ($errors as $error) {
                ?>
                <div class="alert-box alert--danger mt--20">
                    <p class="alert-box__text"><?=$error?></p>
                </div>
                <?php
                    }
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