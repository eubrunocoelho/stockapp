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
            <h1 class="main-heading__title">Cadastrar Gestor</h1>
            <span class="main-heading__breadcrumbs"><a class="breadcrumbs__link" href="<?=$basePath?>/gestores/register">Cadastrar Gestor</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/gestores">Gestores</a> / <a class="breadcrumbs__link" href="<?=$basePath?>/dashboard">Dashboard</a></span>
        </div>
        <section class="section container">
            <div class="box">
                <div class="box-heading">
                    <h1 class="box-heading__title">Cadastrar Gestor</h1>
                </div>
                <form class="form" action="<?=$basePath?>/gestores/register" method="POST" enctype="multipart/form-data">
                    <div class="form__group">
                        <label class="form__label">Foto de Perfil:</label>
                        <input type="file" id="upload" name="img_profile" hidden>
                        <label for="upload" class="form__custom-upload">Escolher arquivo...</label>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Nome: <span class="text--danger">*</span></label>
                        <input type="text" class="form__input" id="nome" name="nome" placeholder="Nome Completo"<?php
                        if (
                            !(empty($persistRegisterValues)) &&
                            (isset($persistRegisterValues['nome'])) &&
                            ($persistRegisterValues['nome'] !== null)
                        ) {
                            echo ' value="' . $persistRegisterValues['nome'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">E-mail: <span class="text--danger">*</span></label>
                        <input type="text" class="form__input" id="email" name="email" placeholder="Endereço de E-mail"<?php
                        if (
                            !(empty($persistRegisterValues)) &&
                            (isset($persistRegisterValues['email'])) &&
                            ($persistRegisterValues['email'] !== null)
                        ) {
                            echo ' value="' . $persistRegisterValues['email'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">CPF: <span class="text--danger">*</span></label>
                        <input type="text" class="form__input" id="cpf" name="cpf" placeholder="CPF"<?php
                        if (
                            !(empty($persistRegisterValues)) &&
                            (isset($persistRegisterValues['cpf'])) &&
                            ($persistRegisterValues['cpf'] !== null)
                        ) {
                            echo ' value="' . $persistRegisterValues['cpf'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Senha: <span class="text--danger">*</span></label>
                        <input type="password" class="form__input" id="senha" name="senha" placeholder="Senha"<?php
                        if (
                            !(empty($persistRegisterValues)) &&
                            (isset($persistRegisterValues['senha'])) &&
                            ($persistRegisterValues['senha'] !== null)
                        ) {
                            echo ' value="' . $persistRegisterValues['senha'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Telefone:</label>
                        <input type="text" class="form__input" id="telefone" name="telefone" placeholder="(DDD) Telefone"<?php
                        if (
                            !(empty($persistRegisterValues)) &&
                            (isset($persistRegisterValues['telefone'])) &&
                            ($persistRegisterValues['telefone'] !== null)
                        ) {
                            echo ' value="' . $persistRegisterValues['telefone'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Endereço:</label>
                        <input type="text" class="form__input" id="endereco" name="endereco" placeholder="Endereço"<?php
                        if (
                            !(empty($persistRegisterValues)) &&
                            (isset($persistRegisterValues['endereco'])) &&
                            ($persistRegisterValues['endereco'] !== null)
                        ) {
                            echo ' value="' . $persistRegisterValues['endereco'] . '"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Cargo: <span class="text--danger">*</span></label>
                        <select class="form__select" id="cargo" name="cargo">
                            <option value="1"<?php
                            if (
                                !(empty($persistRegisterValues)) &&
                                (isset($persistRegisterValues['cargo'])) &&
                                ($persistRegisterValues['cargo'] == 1)
                            ) {
                                echo ' selected';
                            }
                            ?>>Administrador</option>
                            <option value="2"<?php
                            if (
                                !(empty($persistRegisterValues)) &&
                                (isset($persistRegisterValues['cargo'])) &&
                                ($persistRegisterValues['cargo'] == 2)
                            ) {
                                echo ' selected';
                            }
                            ?>>Gestor</option>
                        </select>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Gênero: <span class="text--danger">*</span></label>
                        <select class="form__select" id="genero" name="genero">
                            <option value="1"<?php
                            if (
                                !(empty($persistRegisterValues)) &&
                                (isset($persistRegisterValues['genero'])) &&
                                ($persistRegisterValues['genero'] == 1)
                            ) {
                                echo ' selected';
                            }
                            ?>>Masculino</option>
                            <option value="2"<?php
                            if (
                                !(empty($persistRegisterValues)) &&
                                (isset($persistRegisterValues['genero'])) &&
                                ($persistRegisterValues['genero'] == 2)
                            ) {
                                echo ' selected';
                            }
                            ?>>Feminino</option>
                        </select>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Status: <span class="text--danger">*</span></label>
                        <select class="form__select" id="status" name="status">
                            <option value="1"<?php
                            if (
                                !(empty($persistRegisterValues)) &&
                                (isset($persistRegisterValues['status'])) &&
                                ($persistRegisterValues['status'] == 1)
                            ) {
                                echo ' selected';
                            }
                            ?>>Ativo</option>
                            <option value="2"<?php
                            if (
                                !(empty($persistRegisterValues)) &&
                                (isset($persistRegisterValues['status'])) &&
                                ($persistRegisterValues['status'] == 2)
                            ) {
                                echo ' selected';
                            }
                            ?>>Inativo</option>
                        </select>
                    </div>
                    <button type="submit" class="btn mt--20">Cadastrar</button>
                </form>
                <?php
                if (
                    (isset($errors)) &&
                    !(empty($errors))
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