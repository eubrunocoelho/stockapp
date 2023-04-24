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
            <span class="main-heading__breadcrumbs"><a class="breadcrumbs__link" href="./cadastrar-gestor.html">Cadastrar Gestor</a> / <a class="breadcrumbs__link" href="./gestores.html">Gestores</a> / <a class="breadcrumbs__link" href="./index.html">Dashboard</a></span>
        </div>
        <section class="section container">
            <div class="box">
                <div class="box-heading">
                    <h1 class="box-heading__title">Cadastrar Gestor</h1>
                </div>
                <form class="form">
                    <div class="form__group">
                        <label class="form__label">Foto de Perfil:</label>
                        <input type="file" id="upload" name="img_profile" hidden>
                        <label for="upload" class="form__custom-upload">Escolher arquivo...</label>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Nome: <span class="text--danger">*</span></label>
                        <input type="text" class="form__input" id="nome" name="nome" placeholder="Ex.: João da Silva">
                    </div>
                    <div class="form__group">
                        <label class="form__label">E-mail: <span class="text--danger">*</span></label>
                        <input type="text" class="form__input" id="email" name="email" placeholder="Ex.: joao@example.com">
                    </div>
                    <div class="form__group">
                        <label class="form__label">CPF: <span class="text--danger">*</span></label>
                        <input type="text" class="form__input" id="cpf" name="cpf" placeholder="Ex.: 000.000.000-00">
                    </div>
                    <div class="form__group">
                        <label class="form__label">Senha: <span class="text--danger">*</span></label>
                        <input type="password" class="form__input" id="senha" name="senha" placeholder="Ex.: 123456">
                    </div>
                    <div class="form__group">
                        <label class="form__label">Telefone:</label>
                        <input type="text" class="form__input" id="telefone" name="telefone" placeholder="Ex.: (00) 0000-0000">
                    </div>
                    <div class="form__group">
                        <label class="form__label">Endereço:</label>
                        <input type="text" class="form__input" id="endereco" name="endereco" placeholder="Ex.: Rua XV de Dezembro, 666">
                    </div>
                    <div class="form__group">
                        <label class="form__label">Cargo: <span class="text--danger">*</span></label>
                        <select class="form__select" id="cargo" name="cargo">
                            <option value="1">Administrador</option>
                            <option value="2">Gestor</option>
                        </select>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Gênero: <span class="text--danger">*</span></label>
                        <select class="form__select" id="genero" name="genero">
                            <option value="1">Mascúlino</option>
                            <option value="2">Feminino</option>
                        </select>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Status: <span class="text--danger">*</span></label>
                        <select class="form__select" id="status" name="status">
                            <option value="1">Ativo</option>
                            <option value="2">Inativo</option>
                        </select>
                    </div>
                    <button type="submit" class="btn mt--20">Cadastrar</button>
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