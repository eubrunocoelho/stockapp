<header class="header">
    <div class="header-navigation container">
        <div class="header-navigation__stick">
            <div class="header-navigation__menu-button" id="openMenu">
                <span><i class="fa-solid fa-bars menu-button__icon"></i></span>
            </div>
            <a href="<?=$basePath?>/dashboard" class="header-navigation__logo">StockApp</a>
        </div>
        <div class="header-navigation__user-area">
            <div class="user-area__ellipse" id="clickUserMenu">
                <?php
                if (
                    (
                        ($gestor['img_url'] == '') ||
                        ($gestor['img_url'] == null)
                    )
                ) {
                ?>
                <img src="<?=$basePath?>/uploads/img/profile/default.jpg" class="user-area__img">
                <?php
                } else {
                ?>
                <img src="<?=$basePath?>/uploads/img/profile/<?=$gestor['img_url']?>" class="user-area__img">
                <?php
                }
                ?>
            </div>
            <div class="user-area__dropdown-menu d--none" id="userMenu">
                <div class="dropdown-menu__heading">
                    <h6 class="dropdown-menu__title"><?=$gestor['nome']?></h6>
                    <p class="dropdown-menu__description"><?=$gestor['cargo']?></p>
                </div>
                <nav class="dropdown-menu__nav">
                    <ul class="dropdown-menu__list">
                        <li class="dropdown-menu__item">
                            <span class="dropdown-menu__icon-box"><i class="fa-solid fa-user dropdown-menu__icon"></i></span>
                            <a href="<?=$basePath?>/gestores/show/<?=$gestor['ID']?>" class="dropdown-menu__link">Perfil</a>
                        </li>
                        <li class="dropdown-menu__item">
                            <span class="dropdown-menu__icon-box"><i class="fa-solid fa-circle-exclamation dropdown-menu__icon"></i></span>
                            <a href="<?=$basePath?>/dashboard/logout" class="dropdown-menu__link">Sair</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>