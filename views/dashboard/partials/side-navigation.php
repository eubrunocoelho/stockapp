<aside class="side-navigation">
    <div class="side-navigation__heading">
        <a href="<?=$basePath?>/dashboard" class="side-navigation__logo">StockApp</a>
        <div class="side-navigation__close-button" id="closeMenu">
            <span><i class="fa-solid fa-xmark close-button__icon"></i></span>
        </div>
    </div>
    <nav class="side-navigation__nav">
        <ul class="side-navigation__menu-list">
            <li class="side-navigation__menu-item">
                <span class="menu-item__icon-box"><i class="fa-solid fa-display menu-item__icon"></i></span>
                <a href="<?=$basePath?>/dashboard" class="side-navigation__menu-link">Dashboard</a>
            </li>
        </ul>
        <div class="side-navigation__group-heading">
            <h6 class="group-heading__title">Gerenciar</h6>
        </div>
        <ul class="side-navigation__menu-list">
            <li class="side-navigation__menu-item">
                <span class="menu-item__icon-box"><i class="fa-solid fa-layer-group menu-item__icon"></i></span>
                <a href="./livros.html" class="side-navigation__menu-link">Livros</a>
            </li>
            <li class="side-navigation__menu-item">
                <span class="menu-item__icon-box"><i class="fa-solid fa-clipboard-user menu-item__icon"></i></span>
                <a href="<?=$basePath?>/gestores" class="side-navigation__menu-link">Gestores</a>
            </li>
        </ul>
        <div class="side-navigation__group-heading">
            <h6 class="group-heading__title">Operacional</h6>
        </div>
        <ul class="side-navigation__menu-list">
            <li class="side-navigation__menu-item">
                <span class="menu-item__icon-box"><i class="fa-solid fa-clock-rotate-left menu-item__icon"></i></span>
                <a href="./historico.html" class="side-navigation__menu-link">Histórico de Entrada/Saída</a>
            </li>
        </ul>
    </nav>
</aside>