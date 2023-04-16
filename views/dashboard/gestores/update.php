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
                        <label class="form__label" for="nome">Nome: <span class="text--danger">(*)</span></label>
                        <input type="text" class="form__input" id="nome" name="nome"<?php
                        if (
                            !empty($persistUpdateValues) &&
                            isset($persistUpdateValues['nome']) &&
                            $persistUpdateValues['nome'] != ''
                        ) {
                            echo ' value="' . $persistUpdateValues['nome'] . '"';
                        } elseif (
                            $persistUpdateValues['nome'] === null
                        ) {
                            echo ' placeholder="Nome Completo"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="email">E-mail: <span class="text--danger">(*)</span></label>
                        <input type="text" class="form__input" id="email" name="email"<?php
                        if (
                            !empty($persistUpdateValues) &&
                            isset($persistUpdateValues['email']) &&
                            $persistUpdateValues['email'] != ''
                        ) {
                            echo ' value="' . $persistUpdateValues['email'] . '"';
                        } elseif (
                            $persistUpdateValues['email'] === null
                        ) {
                            echo ' placeholder="Endereço de E-mail"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="cpf">CPF: <span class="text--danger">(*)</span></label>
                        <input type="text" class="form__input" id="cpf" name="cpf"<?php
                        if (
                            !empty($persistUpdateValues) &&
                            isset($persistUpdateValues['cpf']) &&
                            $persistUpdateValues['cpf'] != ''
                        ) {
                            echo ' value="' . $persistUpdateValues['cpf'] . '"';
                        } elseif (
                            $persistUpdateValues['cpf'] === null
                        ) {
                            echo ' placeholder="Ex.: 000.000.000-00"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="telefone">Telefone:</label>
                        <input type="text" class="form__input" id="telefone" name="telefone"<?php
                        if (
                            !empty($persistUpdateValues) &&
                            isset($persistUpdateValues['telefone']) &&
                            $persistUpdateValues['telefone'] != ''
                        ) {
                            echo ' value="' . $persistUpdateValues['telefone'] . '"';
                        } elseif (
                            $persistUpdateValues['telefone'] === null
                        ) {
                            echo ' placeholder="Ex.: (00) 0000-0000"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="endereco">Endereço:</label>
                        <input type="text" class="form__input" id="endereco" name="endereco"<?php
                        if (
                            !empty($persistUpdateValues) &&
                            isset($persistUpdateValues['endereco']) &&
                            $persistUpdateValues['endereco'] != ''
                        ) {
                            echo ' value="' . $persistUpdateValues['endereco'] . '"';
                        } elseif (
                            $persistUpdateValues['endereco'] === null
                        ) {
                            echo ' placeholder="Ex.: Rua XV de Dezembro, 777"';
                        }
                        ?>>
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="cargo">Cargo: <span class="text--danger">(*)</span></label>
                        <select class="form__select" id="cargo" name="cargo">
                            <option value="1"<?php
                            if (
                                !empty($persistUpdateValues) &&
                                isset($persistUpdateValues['cargo']) &&
                                $persistUpdateValues['cargo'] == 1
                            ) {
                                echo ' selected';
                            }
                            ?>>Administrador</option>
                            <option value="2"<?php
                            if (
                                !empty($persistUpdateValues) &&
                                isset($persistUpdateValues['cargo']) &&
                                $persistUpdateValues['cargo'] == 2
                            ) {
                                echo ' selected';
                            }
                            ?>>Gestor</option>
                        </select>
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="genero">Gênero: <span class="text--danger">(*)</span></label>
                        <select class="form__select" id="genero" name="genero">
                            <option value="1"<?php
                            if (
                                !empty($persistUpdateValues) &&
                                isset($persistUpdateValues['genero']) &&
                                $persistUpdateValues['genero'] == 1
                            ) {
                                echo ' selected';
                            }
                            ?>>Masculino</option>
                            <option value="2"<?php
                            if (
                                !empty($persistUpdateValues) &&
                                isset($persistUpdateValues['genero']) &&
                                $persistUpdateValues['genero'] == 2
                            ) {
                                echo ' selected';
                            }
                            ?>>Feminino</option>
                        </select>
                    </div>
                    <?php
                    if ($authorize['update']['status']) {
                    ?>
                    <div class="form__group">
                        <label class="form__label" for="status">Status: <span class="text--danger">(*)</span></label>
                        <select class="form__select" id="status" name="status">
                            <option value="1"<?php
                            if (
                                !empty($persistUpdateValues) &&
                                isset($persistUpdateValues['status']) &&
                                $persistUpdateValues['status'] == 1
                            ) {
                                echo ' selected';
                            }
                            ?>>Ativo</option>
                            <option value="2"<?php
                            if (
                                !empty($persistUpdateValues) &&
                                isset($persistUpdateValues['status']) &&
                                $persistUpdateValues['status'] == 2
                            ) {
                                echo ' selected';
                            }
                            ?>>Inativo</option>
                        </select>
                    </div>
                    <?php
                    }
                    ?>
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