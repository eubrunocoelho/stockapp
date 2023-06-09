<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Entrar - StockApp</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="<?=$basePath?>/assets/css/reset.css">
    <link rel="stylesheet" href="<?=$basePath?>/assets/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <main class="main container">
        <section class="login">
            <?php
            if (
                !(empty($messages)) &&
                (array_key_exists('message.warning', $messages))
            ) {
            ?>
            <div class="alert-box alert--warning mt--20">
                <p class="alert-box__text"><?=$messages['message.warning']?></p>
            </div>
            <?php
            }
            ?>
            <div class="login-heading">
                <h1 class="login-heading__title">StockApp</h1>
                <p class="login-heading__description">Entrar</p>
            </div>
            <form class="form" action="<?=$basePath?>/login" method="POST">
                <div class="form__group">
                    <label class="form__label" for="user">E-mail:</label>
                    <input type="text" class="form__input" id="email" name="email" placeholder="E-mail"<?php
                    if (
                        !(empty($persistLoginValues)) &&
                        (isset($persistLoginValues['email'])) &&
                        ($persistLoginValues['email'] !== null)
                    ) {
                        echo ' value="' . $persistLoginValues['email'] . '"';
                    }
                    ?>>
                </div>
                <div class="form__group">
                    <label class="form__label" for="password">Senha:</label>
                    <input type="password" class="form__input" id="senha" name="senha" placeholder="Senha"<?php
                    if (
                        !(empty($persistLoginValues)) &&
                        (isset($persistLoginValues['senha'])) &&
                        ($persistLoginValues['senha'] !== null)
                    ) {
                        echo ' value="' . $persistLoginValues['senha'] . '"';
                    }
                    ?>>
                </div>
                <button class="btn">Entrar</button>
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
        </section>
    </main>
</body>
</html>