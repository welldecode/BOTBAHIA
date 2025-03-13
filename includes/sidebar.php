<style>
  a.disabled {
    pointer-events: none;
    cursor: default;
    color: gray;
  }
</style>

<header class="header -light-transparent">
  <div class="header__inner"><a href="/perfil" class="header__user">
      <div class="user-info">
        <div class="user-info__avatar"><svg viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="37" y="37" width="36" height="36" rx="18" transform="rotate(-180 37 37)" stroke="#6320EE"
              stroke-width="2" stroke-dasharray="112.36854553222656, 112.36854553222656"></rect>
          </svg><img style="width: 35px; height: 35px; object-fit: cover;" src="<?= $_SESSION['avatar'] ?>"
            alt="Foto do perfil"></div>
        <div class="user-info__info">
          <p class="headline-2 mobile-heading-5">Kauã Skierzynski</p>
        </div>
      </div>
    </a>
    <div class="header__logo">BOT BAHIA</div>
    <div class="header-search"></div>
  </div>
</header>
<div class="sidebar-layer"></div>
<div class="sidebar-mobile-open">
  <div></div>
  <div></div>
  <div></div>
</div>
<div class="sidebar -hidden">
  <div class="sidebar__inner">
    <div class="sidebar-mobile-header">
      <div class="sidebar-mobile-header__inner -has-background">
      </div>
    </div>
    <div class="sidebar-header"><!-- SUA LOGO -->
      <div>
        <label for="themeSwitch">Tema:</label>
        <select id="themeSwitch">
          <option value="" selected>Padrão</option>
          <option value="default">Branco</option>
          <option value="orange">Orange</option>
          <option value="blue">Blue</option>
          <option value="green">Green</option>
          <option value="red">Red</option>
        </select>
      </div>
    </div>
    <div class="sidebar__scrollable" style="overflow-y: auto;">
      <div
        class="os-host os-host-foreign os-theme-light os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-scrollbar-vertical-hidden os-host-transition">
        <div class="os-resize-observer-host observed">
          <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
        </div>
        <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
          <div class="os-resize-observer"></div>
        </div>
        <div class="os-content-glue" style="margin: 0px; width: 279px; height: 100vh;"></div>
        <div class="os-padding">
          <div class="os-viewport os-viewport-native-scrollbars-invisible">
            <div class="os-content" style="padding: 0px; height: 100%; width: 100%; overflow-y: auto;">
              <div class="sidebar-user"><a href="/perfil/index.php" class="sidebar_usuario">
                  <div style="margin-left: 20px;" id="user-info" class="user-info">
                    <div class="user-info__avatar"><svg viewBox="0 0 38 38" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect x="37" y="37" width="36" height="36" rx="18" transform="rotate(-180 37 37)"
                          stroke="#6320EE" stroke-width="2" stroke-dasharray="112.36854553222656, 112.36854553222656">
                        </rect>
                      </svg><img style="width: 36px; height: 36px; object-fit: cover;" src="<?= $_SESSION['avatar']; ?>"
                        alt=""></div>
                    <div class="user-info__info">
                      <p class="headline-2 mobile-heading-5"><?= $_SESSION['nome_usuario'] ?></p>
                    </div>
                  </div>
                </a>

                <a href="/configuracoes.php" class="sidebar-user__button"><img src="/assets/gear-22px.ff194763.svg"
                    alt="" style="transform: rotate(60deg);"></a>

              </div>
              <div class="sidebar__group">
                <div class="sidebar__group-title caption-1">Navegação</div>
                <ul class="sidebar__group-list">
                  <li><a aria-current="page" href="/dash.php" class="router-link-active -active sidebar-nav-item"
                      data-soon-text="Em breve">
                      <div class="sidebar-nav-item__icon"><svg viewBox="0 0 28 28" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <g clip-path="url(#sidebar-home-svg-clip)">
                            <path
                              d="M22.666 24.333h-5.333a.667.667 0 0 1-.666-.666v-5.334a.666.666 0 0 0-.667-.666h-4a.667.667 0 0 0-.667.666v5.334a.667.667 0 0 1-.667.666H5.333a.667.667 0 0 1-.667-.666v-7.709c.075-.05.16-.078.228-.14L14 7.793l9.114 8.037c.064.058.148.083.22.133v7.705a.667.667 0 0 1-.668.666Z"
                              fill="#555656"></path>
                            <path class="-hover"
                              d="M23.997 14.828 14.44 6.4a.667.667 0 0 0-.882 0L4.004 14.83a.667.667 0 0 1-.94-.05l-.893-.992a.666.666 0 0 1 .05-.941l10.667-9.417a1.66 1.66 0 0 1 2.227 0l4.218 3.724V5A.667.667 0 0 1 20 4.333h2.666a.667.667 0 0 1 .667.667v5.684l2.446 2.16a.666.666 0 0 1 .05.941l-.891.993a.667.667 0 0 1-.941.05Z"
                              fill="#A8A9AB"></path>
                          </g>
                          <defs>
                            <clipPath id="sidebar-home-svg-clip">
                              <path fill="#fff" transform="translate(2 3)" d="M0 0h24v21.333H0z"></path>
                            </clipPath>
                          </defs>
                        </svg></div>
                      <p class="sidebar-nav-item__title headline-3 mobile-heading-5">Dashboard</p><!---->
                    </a></li>
                  <?php if ($_SESSION['cargo'] == 1) { ?>
                    <li><a href="/anuncios/index.php" class="sidebar-nav-item" data-soon-text="Em breve">
                        <div class="sidebar-nav-item__icon"><svg fill="#4B4E52" version="1.1" id="XMLID_65_"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            viewBox="0 0 24 24" xml:space="preserve">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                              <g id="article">
                                <g>
                                  <path
                                    d="M20.5,22H4c-0.2,0-0.3,0-0.5,0C1.6,22,0,20.4,0,18.5V6h5V2h19v16.5C24,20.4,22.4,22,20.5,22z M6.7,20h13.8 c0.8,0,1.5-0.7,1.5-1.5V4H7v14.5C7,19,6.9,19.5,6.7,20z M2,8v10.5C2,19.3,2.7,20,3.5,20S5,19.3,5,18.5V8H2z">
                                  </path>
                                </g>
                                <g>
                                  <rect x="15" y="6" width="5" height="6"></rect>
                                </g>
                                <g>
                                  <rect x="9" y="6" width="4" height="2"></rect>
                                </g>
                                <g>
                                  <rect x="9" y="10" width="4" height="2"></rect>
                                </g>
                                <g>
                                  <rect x="9" y="14" width="11" height="2"></rect>
                                </g>
                              </g>
                            </g>
                          </svg></div>
                        <p class="sidebar-nav-item__title headline-3 mobile-heading-5">Anuncios</p><!---->
                      </a></li>
                  <?php } ?>
                </ul>
              </div>
              <div class="sidebar__group">
                <div class="sidebar__group-title caption-1">Sistema</div>
                <ul class="sidebar__group-list">
                  <li><a href="/informacoes.php" class="sidebar-nav-item" data-soon-text="Em breve">
                      <div class="sidebar-nav-item__icon"><svg xmlns="http://www.w3.org/2000/svg" width="51" height="59"
                          viewBox="0 0 51 59" fill="none" style="height: 28px;">
                          <g clip-path="url(#clip0_112_466)">
                            <path opacity="0.4"
                              d="M36.7151 6.51715C34.4572 8.61661 32.3465 10.8692 30.3982 13.2588C27.3353 9.03627 23.5375 4.75646 19.2364 0.764297C8.19582 11.0082 0.359802 24.36 0.359802 32.405C0.359802 46.7029 11.6284 58.2928 25.5285 58.2928C39.4286 58.2928 50.6973 46.7029 50.6973 32.405C50.6973 26.4196 44.8545 14.0745 36.7151 6.51715V6.51715ZM36.5893 47.7388C33.4417 49.94 29.6908 51.1146 25.8499 51.1018C20.9139 51.1018 16.3633 49.4287 13.0341 46.3916C11.3017 44.8135 9.92712 42.883 9.00257 40.7298C8.03964 38.4972 7.55087 36.0455 7.55087 33.4331C7.55087 26.7555 12.3599 21.4791 19.2701 13.1947C22.6409 17.0813 21.3476 15.4172 30.5848 27.1937L37.5511 19.2453C40.2837 23.7678 40.6725 24.3679 41.4657 25.8858C43.3609 29.4944 43.9561 33.6453 43.1511 37.641C42.3096 41.7815 39.9781 45.3714 36.5893 47.7388V47.7388Z"
                              fill="#A8A9AB"></path>
                            <path
                              d="M34.5275 44.7928C31.9824 46.5673 28.9525 47.5148 25.8499 47.5063C17.7431 47.5063 11.1464 42.1422 11.1464 33.4331C11.1464 29.0949 13.8779 25.2724 19.3251 18.742C20.1038 19.6409 30.4297 32.8253 30.4297 32.8253L37.0174 25.3106C37.4826 26.0803 37.9062 26.8331 38.2837 27.5578C41.3568 33.4219 40.0601 40.9287 34.5275 44.7962V44.7928Z"
                              fill="#A8A9AB"></path>
                          </g>
                          <defs>
                            <clipPath id="clip0_112_466">
                              <rect width="50.3375" height="57.5285" fill="white"
                                transform="translate(0.359802 0.764297)"></rect>
                            </clipPath>
                          </defs>
                        </svg></div>
                      <p class="sidebar-nav-item__title headline-3 mobile-heading-5">Informações Gerais</p><!---->
                    </a></li>
                  <!-- <li><a href="/relatorios.php" class="-soon sidebar-nav-item disabled" data-soon-text="Breve">
                      <div class="sidebar-nav-item__icon"><svg xmlns="http://www.w3.org/2000/svg" width="51" height="59"
                          viewBox="0 0 51 59" fill="none" style="height: 28px;">
                          <g clip-path="url(#clip0_112_466)">
                            <path opacity="0.4"
                              d="M36.7151 6.51715C34.4572 8.61661 32.3465 10.8692 30.3982 13.2588C27.3353 9.03627 23.5375 4.75646 19.2364 0.764297C8.19582 11.0082 0.359802 24.36 0.359802 32.405C0.359802 46.7029 11.6284 58.2928 25.5285 58.2928C39.4286 58.2928 50.6973 46.7029 50.6973 32.405C50.6973 26.4196 44.8545 14.0745 36.7151 6.51715V6.51715ZM36.5893 47.7388C33.4417 49.94 29.6908 51.1146 25.8499 51.1018C20.9139 51.1018 16.3633 49.4287 13.0341 46.3916C11.3017 44.8135 9.92712 42.883 9.00257 40.7298C8.03964 38.4972 7.55087 36.0455 7.55087 33.4331C7.55087 26.7555 12.3599 21.4791 19.2701 13.1947C22.6409 17.0813 21.3476 15.4172 30.5848 27.1937L37.5511 19.2453C40.2837 23.7678 40.6725 24.3679 41.4657 25.8858C43.3609 29.4944 43.9561 33.6453 43.1511 37.641C42.3096 41.7815 39.9781 45.3714 36.5893 47.7388V47.7388Z"
                              fill="#A8A9AB"></path>
                            <path
                              d="M34.5275 44.7928C31.9824 46.5673 28.9525 47.5148 25.8499 47.5063C17.7431 47.5063 11.1464 42.1422 11.1464 33.4331C11.1464 29.0949 13.8779 25.2724 19.3251 18.742C20.1038 19.6409 30.4297 32.8253 30.4297 32.8253L37.0174 25.3106C37.4826 26.0803 37.9062 26.8331 38.2837 27.5578C41.3568 33.4219 40.0601 40.9287 34.5275 44.7962V44.7928Z"
                              fill="#A8A9AB"></path>
                          </g>
                          <defs>
                            <clipPath id="clip0_112_466">
                              <rect width="50.3375" height="57.5285" fill="white"
                                transform="translate(0.359802 0.764297)"></rect>
                            </clipPath>
                          </defs>
                        </svg></div>
                      <p class="sidebar-nav-item__title headline-3 mobile-heading-5">Relatórios</p>
                    </a></li> -->
                  <?php if ($_SESSION['cargo'] == 1) { ?>
                    <li><a href="/criar_conta.php" class="sidebar-nav-item" data-soon-text="Em breve">
                        <div class="sidebar-nav-item__icon"><svg viewBox="0 0 28 28" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.4"
                              d="M5.72 24C4.58 22.8841 3.6752 21.5701 3.0056 20.0581C2.3352 18.5469 2 16.9188 2 15.1739C2 13.4899 2.3152 11.9072 2.9456 10.4261C3.5752 8.94493 4.43 7.65652 5.51 6.56087C6.59 5.46522 7.86 4.59762 9.32 3.95809C10.78 3.31936 12.34 3 14 3C15.66 3 17.22 3.31936 18.68 3.95809C20.14 4.59762 21.41 5.46522 22.49 6.56087C23.57 7.65652 24.4248 8.94493 25.0544 10.4261C25.6848 11.9072 26 13.4899 26 15.1739C26 16.9188 25.6652 18.5522 24.9956 20.0739C24.3252 21.5957 23.42 22.9043 22.28 24L20.6 22.2957C21.52 21.4029 22.25 20.343 22.79 19.1158C23.33 17.8879 23.6 16.5739 23.6 15.1739C23.6 12.4551 22.67 10.1522 20.81 8.26522C18.95 6.37826 16.68 5.43478 14 5.43478C11.32 5.43478 9.05 6.37826 7.19 8.26522C5.33 10.1522 4.4 12.4551 4.4 15.1739C4.4 16.5739 4.67 17.8826 5.21 19.1C5.75 20.3174 6.49 21.3725 7.43 22.2652L5.72 24ZM9.11 20.5609C8.41 19.8913 7.85 19.0951 7.43 18.1723C7.01 17.2488 6.8 16.2493 6.8 15.1739C6.8 13.1449 7.5 11.4203 8.9 10C10.3 8.57971 12 7.86957 14 7.86957C16 7.86957 17.7 8.57971 19.1 10C20.5 11.4203 21.2 13.1449 21.2 15.1739C21.2 16.2493 20.99 17.2536 20.57 18.187C20.15 19.1203 19.59 19.9116 18.89 20.5609L17.18 18.8261C17.68 18.3594 18.0748 17.8116 18.3644 17.1826C18.6548 16.5536 18.8 15.8841 18.8 15.1739C18.8 13.8348 18.33 12.6884 17.39 11.7348C16.45 10.7812 15.32 10.3043 14 10.3043C12.68 10.3043 11.55 10.7812 10.61 11.7348C9.67 12.6884 9.2 13.8348 9.2 15.1739C9.2 15.9043 9.3452 16.5788 9.6356 17.1972C9.9252 17.8165 10.32 18.3594 10.82 18.8261L9.11 20.5609ZM14 17.6087C13.34 17.6087 12.7752 17.3701 12.3056 16.8929C11.8352 16.4165 11.6 15.8435 11.6 15.1739C11.6 14.5043 11.8352 13.931 12.3056 13.4537C12.7752 12.9773 13.34 12.7391 14 12.7391C14.66 12.7391 15.2252 12.9773 15.6956 13.4537C16.1652 13.931 16.4 14.5043 16.4 15.1739C16.4 15.8435 16.1652 16.4165 15.6956 16.8929C15.2252 17.3701 14.66 17.6087 14 17.6087Z"
                              fill="#A8A9AB"></path>
                            <path class="-hover"
                              d="M9.11078 20.5609C8.41078 19.8913 7.85078 19.0951 7.43078 18.1723C7.01078 17.2488 6.80078 16.2493 6.80078 15.1739C6.80078 13.1449 7.50078 11.4203 8.90078 10C10.3008 8.57971 12.0008 7.86957 14.0008 7.86957C16.0008 7.86957 17.7008 8.57971 19.1008 10C20.5008 11.4203 21.2008 13.1449 21.2008 15.1739C21.2008 16.2493 20.9908 17.2536 20.5708 18.187C20.1508 19.1203 19.5908 19.9116 18.8908 20.5609L17.1808 18.8261C17.6808 18.3594 18.0756 17.8116 18.3652 17.1826C18.6556 16.5536 18.8008 15.8841 18.8008 15.1739C18.8008 13.8348 18.3308 12.6884 17.3908 11.7348C16.4508 10.7812 15.3208 10.3044 14.0008 10.3044C12.6808 10.3044 11.5508 10.7812 10.6108 11.7348C9.67078 12.6884 9.20078 13.8348 9.20078 15.1739C9.20078 15.9044 9.34598 16.5788 9.63638 17.1972C9.92598 17.8165 10.3208 18.3594 10.8208 18.8261L9.11078 20.5609ZM14.0008 17.6087C13.3408 17.6087 12.776 17.3701 12.3064 16.8929C11.836 16.4165 11.6008 15.8435 11.6008 15.1739C11.6008 14.5044 11.836 13.931 12.3064 13.4537C12.776 12.9773 13.3408 12.7391 14.0008 12.7391C14.6608 12.7391 15.226 12.9773 15.6964 13.4537C16.166 13.931 16.4008 14.5044 16.4008 15.1739C16.4008 15.8435 16.166 16.4165 15.6964 16.8929C15.226 17.3701 14.6608 17.6087 14.0008 17.6087Z"
                              fill="#A8A9AB"></path>
                          </svg></div>
                        <p class="sidebar-nav-item__title headline-3 mobile-heading-5">Criar nova conta</p><!---->
                      </a></li>
                  <?php } ?>
                  <?php if ($_SESSION['cargo'] == 1) { ?>
                    <li><a href="/gerenciar_user/index.php" class="sidebar-nav-item" data-soon-text="Em breve">
                        <div class="sidebar-nav-item__icon">
                          <img src="/assets/gerenciar-usuario.svg" alt="">
                        </div>
                        <p class="sidebar-nav-item__title headline-3 mobile-heading-5">Gerenciar usuário</p><!---->
                      </a></li>
                  <?php } ?>
                  <?php if ($_SESSION['cargo'] == 1) { ?>
                    <li><a href="/bot_opcoes.php" class="sidebar-nav-item">
                        <div class="sidebar-nav-item__icon"><svg width="28px" height="28px" viewBox="0 0 24 24"
                            fill="none" xmlns="http://www.w3.org/2000/svg" aria-labelledby="controlCentreIconTitle"
                            stroke="#707070" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            color="#000000">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                              <title id="controlCentreIconTitle">Control Centre</title>
                              <path
                                d="M4 6.5C4 4.567 5.567 3 7.5 3L16.5 3C18.433 3 20 4.567 20 6.5V6.5C20 8.433 18.433 10 16.5 10L7.5 10C5.567 10 4 8.433 4 6.5V6.5Z">
                              </path>
                              <path
                                d="M20 17.5C20 19.433 18.433 21 16.5 21L7.5 21C5.567 21 4 19.433 4 17.5V17.5C4 15.567 5.567 14 7.5 14L16.5 14C18.433 14 20 15.567 20 17.5V17.5Z">
                              </path>
                              <circle cx="16.5" cy="17.5" r="1"></circle>
                              <circle cx="7.5" cy="6.5" r="1"></circle>
                            </g>
                          </svg></div>
                        <p class="sidebar-nav-item__title headline-3 mobile-heading-5">Controle BOT</p><!---->
                      </a></li>
                  <?php } ?>
                 <!--  <li><a href="/config_cartao/index.php" class="-soon sidebar-nav-item disabled" data-soon-text="Breve">
                      <div class="sidebar-nav-item__icon"><svg width="64px" height="64px" viewBox="0 0 24 24"
                          fill="none" xmlns="http://www.w3.org/2000/svg">
                          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                          <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                          <g id="SVGRepo_iconCarrier">
                            <path opacity="0.5"
                              d="M9.99976 20H13.9998C17.771 20 19.6566 20 20.8282 18.8284C21.9998 17.6569 21.9998 15.7712 21.9998 12C21.9998 11.5581 21.9981 10.392 21.9962 10H2C1.99811 10.392 1.99976 11.5581 1.99976 12C1.99976 15.7712 1.99976 17.6569 3.17133 18.8284C4.34291 20 6.22852 20 9.99976 20Z"
                              fill="#4B4E52"></path>
                            <path
                              d="M5.25 13.5C5.25 13.0858 5.58579 12.75 6 12.75H8C8.41421 12.75 8.75 13.0858 8.75 13.5C8.75 13.9142 8.41421 14.25 8 14.25H6C5.58579 14.25 5.25 13.9142 5.25 13.5Z"
                              fill="#4B4E52"></path>
                            <path
                              d="M5.25 16.5C5.25 16.0858 5.58579 15.75 6 15.75H10C10.4142 15.75 10.75 16.0858 10.75 16.5C10.75 16.9142 10.4142 17.25 10 17.25H6C5.58579 17.25 5.25 16.9142 5.25 16.5Z"
                              fill="#4B4E52"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M17.1972 12.2933C16.8744 12.2499 16.4776 12.25 16.0447 12.25H15.9553C15.5224 12.25 15.1256 12.2499 14.8028 12.2933C14.4473 12.3411 14.0716 12.4535 13.7626 12.7626C13.4535 13.0716 13.3411 13.4473 13.2933 13.8028C13.2499 14.1256 13.2499 14.5224 13.25 14.9553L13.25 15L13.25 15.0448C13.2499 15.4776 13.2499 15.8744 13.2933 16.1972C13.3411 16.5527 13.4535 16.9284 13.7626 17.2374C14.0716 17.5465 14.4473 17.6589 14.8028 17.7067C15.1256 17.7501 15.5224 17.7501 15.9553 17.75L16 17.75L16.0447 17.75C16.4776 17.7501 16.8744 17.7501 17.1972 17.7067C17.5527 17.6589 17.9284 17.5465 18.2374 17.2374C18.5465 16.9284 18.6589 16.5527 18.7067 16.1972C18.7501 15.8744 18.7501 15.4776 18.75 15.0447V14.9553C18.7501 14.5224 18.7501 14.1256 18.7067 13.8028C18.6589 13.4473 18.5465 13.0716 18.2374 12.7626C17.9284 12.4535 17.5527 12.3411 17.1972 12.2933ZM14.8257 13.8219L14.8233 13.8232L14.8219 13.8257C14.8209 13.8276 14.8192 13.8309 14.8172 13.836C14.8082 13.8578 14.7929 13.9061 14.7799 14.0027C14.7516 14.2134 14.75 14.5074 14.75 15C14.75 15.4926 14.7516 15.7866 14.7799 15.9973C14.7929 16.0939 14.8082 16.1423 14.8172 16.164C14.8184 16.1671 14.8195 16.1695 14.8204 16.1714C14.821 16.1726 14.8215 16.1736 14.8219 16.1743L14.8232 16.1768L14.8257 16.1781C14.8276 16.1792 14.8309 16.1808 14.836 16.1828C14.8577 16.1918 14.9061 16.2071 15.0027 16.2201C15.2134 16.2484 15.5074 16.25 16 16.25C16.4926 16.25 16.7866 16.2484 16.9973 16.2201C17.0939 16.2071 17.1423 16.1918 17.164 16.1828C17.1691 16.1808 17.1724 16.1792 17.1743 16.1781L17.1768 16.1768L17.1781 16.1743C17.1791 16.1724 17.1808 16.1691 17.1828 16.164C17.1918 16.1423 17.2071 16.0939 17.2201 15.9973C17.2484 15.7866 17.25 15.4926 17.25 15C17.25 14.5074 17.2484 14.2134 17.2201 14.0027C17.2071 13.9061 17.1918 13.8578 17.1828 13.836C17.1808 13.8309 17.1791 13.8276 17.1781 13.8257L17.1768 13.8232L17.1743 13.8219C17.1724 13.8209 17.1691 13.8192 17.164 13.8172C17.1423 13.8082 17.0939 13.7929 16.9973 13.7799C16.7866 13.7516 16.4926 13.75 16 13.75C15.5074 13.75 15.2134 13.7516 15.0027 13.7799C14.9061 13.7929 14.8577 13.8082 14.836 13.8172C14.8309 13.8192 14.8276 13.8209 14.8257 13.8219Z"
                              fill="#4B4E52"></path>
                            <path
                              d="M9.99484 4H14.0052C17.7861 4 19.6766 4 20.8512 5.11578C21.6969 5.91916 21.9337 7.07507 22 9V10H2V9C2.0663 7.07507 2.3031 5.91916 3.14881 5.11578C4.3234 4 6.21388 4 9.99484 4Z"
                              fill="#4B4E52"></path>
                          </g>
                        </svg></div>
                      <p class="sidebar-nav-item__title headline-3 mobile-heading-5">Config. Cartão</p>
                    </a></li> -->
                  <li><a href="/credenciais/index.php" class="sidebar-nav-item" data-soon-text="Em breve">
                      <div class="sidebar-nav-item__icon"><svg height="64px" width="64px" version="1.1"
                          id="Uploaded to svgrepo.com" xmlns="http://www.w3.org/2000/svg"
                          xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" xml:space="preserve"
                          fill="#000000">
                          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                          <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                          <g id="SVGRepo_iconCarrier">
                            <style type="text/css">
                              .hatch_een {
                                fill: #555656;
                              }

                              .hatch_twee {
                                fill: #A8A9AB;
                              }
                            </style>
                            <g>
                              <path class="hatch_twee"
                                d="M21.641,23.144l5.498-5.498c0.006,0.117,0.019,0.228,0.058,0.322c0.08,0.193,0.276,0.368,0.492,0.542 l-5.177,5.177c-0.174-0.215-0.348-0.411-0.542-0.492C21.875,23.158,21.759,23.15,21.641,23.144z M19.803,21.031 c0.147,0.355,0.001,0.913-0.011,1.376l6.619-6.619c-0.312,0.008-0.67,0.08-0.977,0.08c-0.149,0-0.287-0.017-0.404-0.065 c-0.01-0.004-0.02-0.014-0.031-0.019L19.785,21C19.79,21.011,19.799,21.021,19.803,21.031z M19.803,17.969 C19.583,18.5,18.5,18.89,18.5,19.5c0,0.057,0.011,0.117,0.028,0.171l5.143-5.143C23.618,14.51,23.557,14.5,23.5,14.5 c-0.61,0-1,1.083-1.531,1.303c-0.116,0.048-0.254,0.065-0.403,0.065c-0.19,0-0.397-0.024-0.602-0.046l-1.144,1.144 C19.859,17.331,19.912,17.706,19.803,17.969z M26.467,23.211c0.23,0,0.433-0.04,0.569-0.175c0.288-0.288,0.146-0.876,0.107-1.394 l-1.505,1.505C25.913,23.167,26.205,23.211,26.467,23.211z M24.769,28.016l0.786,0.628L26,28.199v-1.414L24.769,28.016z M21,24.177 v1.022l0.799-0.799c-0.072-0.091-0.161-0.203-0.225-0.268c0,0,0,0-0.001,0c-0.003,0-0.006,0-0.009,0 c-0.128,0-0.267,0.018-0.406,0.033L21,24.177z M25.841,24.165c-0.139-0.016-0.278-0.033-0.406-0.033c-0.003,0-0.006,0-0.009,0 c-0.07,0.072-0.175,0.205-0.25,0.299C24.839,24.857,24.33,25.5,23.5,25.5c-0.069,0-0.132-0.011-0.196-0.019L21,27.785V29 l0.995-0.796L26,24.199v-0.022C25.948,24.172,25.889,24.171,25.841,24.165z">
                              </path>
                              <path class="hatch_een"
                                d="M23.5,10h-20C3.224,10,3,9.776,3,9.5S3.224,9,3.5,9h20C23.776,9,24,9.224,24,9.5S23.776,10,23.5,10z M14,12.5c0-0.276-0.224-0.5-0.5-0.5h-10C3.224,12,3,12.224,3,12.5S3.224,13,3.5,13h10C13.776,13,14,12.776,14,12.5z M14,15.5 c0-0.276-0.224-0.5-0.5-0.5h-10C3.224,15,3,15.224,3,15.5S3.224,16,3.5,16h10C13.776,16,14,15.776,14,15.5z M14,23.5 c0-0.276-0.224-0.5-0.5-0.5h-10C3.224,23,3,23.224,3,23.5S3.224,24,3.5,24h10C13.776,24,14,23.776,14,23.5z M32,7v19 c0,0.552-0.448,1-1,1h-4v2c0,0.384-0.22,0.735-0.567,0.901C26.295,29.968,26.147,30,26,30c-0.223,0-0.444-0.075-0.625-0.219 l-1.875-1.5l-1.875,1.5C21.444,29.925,21.223,30,21,30c-0.147,0-0.295-0.032-0.433-0.099C20.22,29.735,20,29.384,20,29v-2H1 c-0.552,0-1-0.448-1-1V7c0-0.552,0.448-1,1-1h30C31.552,6,32,6.448,32,7z M26,24.177c-0.052-0.005-0.111-0.006-0.159-0.012 c-0.139-0.016-0.278-0.033-0.406-0.033c-0.003,0-0.006,0-0.009,0c-0.07,0.072-0.175,0.205-0.25,0.299 C24.839,24.857,24.33,25.5,23.5,25.5s-1.339-0.643-1.675-1.069c-0.074-0.094-0.179-0.227-0.25-0.299c0,0,0,0,0,0 c-0.003,0-0.006,0-0.009,0c-0.128,0-0.267,0.018-0.406,0.033c-0.049,0.005-0.108,0.006-0.159,0.012V29l2.5-2l2.5,2V24.177z M27.035,23.036c0.414-0.414-0.067-1.455,0.161-2.005C27.417,20.5,28.5,20.11,28.5,19.5s-1.083-1-1.303-1.531 c-0.228-0.55,0.253-1.59-0.161-2.004c-0.136-0.136-0.338-0.175-0.569-0.175c-0.325,0-0.706,0.079-1.032,0.079 c-0.149,0-0.287-0.017-0.404-0.065C24.5,15.583,24.11,14.5,23.5,14.5s-1,1.083-1.531,1.303c-0.116,0.048-0.254,0.065-0.403,0.065 c-0.326,0-0.707-0.079-1.032-0.079c-0.23,0-0.433,0.04-0.569,0.175c-0.414,0.414,0.067,1.455-0.161,2.005 C19.583,18.5,18.5,18.89,18.5,19.5c0,0.61,1.083,1,1.303,1.531c0.228,0.55-0.253,1.59,0.161,2.004 c0.136,0.136,0.338,0.175,0.569,0.175c0.325,0,0.706-0.079,1.032-0.079c0.149,0,0.287,0.017,0.404,0.065 C22.5,23.417,22.89,24.5,23.5,24.5s1-1.083,1.531-1.303c0.116-0.048,0.254-0.065,0.403-0.065c0.326,0,0.707,0.079,1.032,0.079 C26.697,23.211,26.9,23.171,27.035,23.036z M31,7H1v19h19v-1.858c-0.292-0.072-0.544-0.201-0.743-0.4 c-0.571-0.571-0.485-1.34-0.423-1.902c0.015-0.137,0.038-0.336,0.044-0.406c-0.069-0.07-0.21-0.182-0.309-0.26 C18.143,20.839,17.5,20.33,17.5,19.5s0.643-1.339,1.069-1.676c0.094-0.075,0.227-0.18,0.299-0.25 c0.001-0.106-0.02-0.287-0.034-0.415c-0.063-0.562-0.149-1.331,0.423-1.902c0.311-0.311,0.74-0.468,1.276-0.468 c0.218,0,0.435,0.024,0.626,0.046c0.139,0.016,0.278,0.033,0.406,0.033c0.003,0,0.006,0,0.01,0c0.07-0.072,0.175-0.205,0.25-0.299 C22.161,14.143,22.67,13.5,23.5,13.5s1.339,0.643,1.675,1.069c0.074,0.094,0.179,0.227,0.25,0.299c0,0,0,0,0,0 c0.003,0,0.006,0,0.009,0c0.128,0,0.267-0.018,0.406-0.033c0.191-0.021,0.408-0.046,0.626-0.046c0.536,0,0.965,0.158,1.276,0.468 c0.571,0.571,0.485,1.341,0.423,1.902c-0.015,0.137-0.038,0.336-0.043,0.405c0.069,0.07,0.21,0.182,0.309,0.26 C28.857,18.161,29.5,18.67,29.5,19.5s-0.643,1.339-1.069,1.675c-0.094,0.075-0.227,0.18-0.299,0.25 c-0.001,0.106,0.02,0.287,0.034,0.415c0.063,0.562,0.149,1.331-0.423,1.902c-0.198,0.198-0.45,0.327-0.743,0.399V26h4V7z">
                              </path>
                            </g>
                          </g>
                        </svg></div>
                      <p class="sidebar-nav-item__title headline-3 mobile-heading-5">Credenciais</p><!---->
                    </a></li>
                </ul>
              </div>

              <div class="sidebar__group">
                <div class="sidebar__group-title caption-1">DMarket</div>
                <ul class="sidebar__group-list">
                  <li><a href="/inventory.php" class="sidebar-nav-item" data-soon-text="Em breve">
                      <div class="sidebar-nav-item__icon"><svg xmlns="http://www.w3.org/2000/svg" width="51" height="59"
                          viewBox="0 0 51 59" fill="none" style="height: 28px;">
                          <g clip-path="url(#clip0_112_466)">
                            <path opacity="0.4"
                              d="M36.7151 6.51715C34.4572 8.61661 32.3465 10.8692 30.3982 13.2588C27.3353 9.03627 23.5375 4.75646 19.2364 0.764297C8.19582 11.0082 0.359802 24.36 0.359802 32.405C0.359802 46.7029 11.6284 58.2928 25.5285 58.2928C39.4286 58.2928 50.6973 46.7029 50.6973 32.405C50.6973 26.4196 44.8545 14.0745 36.7151 6.51715V6.51715ZM36.5893 47.7388C33.4417 49.94 29.6908 51.1146 25.8499 51.1018C20.9139 51.1018 16.3633 49.4287 13.0341 46.3916C11.3017 44.8135 9.92712 42.883 9.00257 40.7298C8.03964 38.4972 7.55087 36.0455 7.55087 33.4331C7.55087 26.7555 12.3599 21.4791 19.2701 13.1947C22.6409 17.0813 21.3476 15.4172 30.5848 27.1937L37.5511 19.2453C40.2837 23.7678 40.6725 24.3679 41.4657 25.8858C43.3609 29.4944 43.9561 33.6453 43.1511 37.641C42.3096 41.7815 39.9781 45.3714 36.5893 47.7388V47.7388Z"
                              fill="#A8A9AB"></path>
                            <path
                              d="M34.5275 44.7928C31.9824 46.5673 28.9525 47.5148 25.8499 47.5063C17.7431 47.5063 11.1464 42.1422 11.1464 33.4331C11.1464 29.0949 13.8779 25.2724 19.3251 18.742C20.1038 19.6409 30.4297 32.8253 30.4297 32.8253L37.0174 25.3106C37.4826 26.0803 37.9062 26.8331 38.2837 27.5578C41.3568 33.4219 40.0601 40.9287 34.5275 44.7962V44.7928Z"
                              fill="#A8A9AB"></path>
                          </g>
                          <defs>
                            <clipPath id="clip0_112_466">
                              <rect width="50.3375" height="57.5285" fill="white"
                                transform="translate(0.359802 0.764297)"></rect>
                            </clipPath>
                          </defs>
                        </svg></div>
                      <p class="sidebar-nav-item__title headline-3 mobile-heading-5">Inventário</p><!---->
                    </a></li>
                  <li><a href="/history.php" class="sidebar-nav-item" data-soon-text="Em breve">
                      <div class="sidebar-nav-item__icon"><svg width="28px" height="28px" viewBox="0 0 24 24"
                          fill="none" xmlns="http://www.w3.org/2000/svg">
                          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                          <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                          <g id="SVGRepo_iconCarrier">
                            <path opacity="0.5" d="M12 8V12L14.5 14.5" stroke="#555656" stroke-width="1.5"
                              stroke-linecap="round" stroke-linejoin="round"></path>
                            <path
                              d="M5.60414 5.60414L5.07381 5.07381V5.07381L5.60414 5.60414ZM4.33776 6.87052L3.58777 6.87429C3.58984 7.28556 3.92272 7.61844 4.33399 7.62051L4.33776 6.87052ZM6.87954 7.6333C7.29375 7.63539 7.63122 7.30129 7.6333 6.88708C7.63538 6.47287 7.30129 6.1354 6.88708 6.13332L6.87954 7.6333ZM5.07496 4.3212C5.07288 3.90699 4.73541 3.5729 4.3212 3.57498C3.90699 3.57706 3.5729 3.91453 3.57498 4.32874L5.07496 4.3212ZM3.82661 10.7849C3.88286 10.3745 3.59578 9.99627 3.1854 9.94002C2.77503 9.88377 2.39675 10.1708 2.3405 10.5812L3.82661 10.7849ZM18.8622 5.13777C15.042 1.31758 8.86873 1.27889 5.07381 5.07381L6.13447 6.13447C9.33358 2.93536 14.5571 2.95395 17.8016 6.19843L18.8622 5.13777ZM5.13777 18.8622C8.95796 22.6824 15.1313 22.7211 18.9262 18.9262L17.8655 17.8655C14.6664 21.0646 9.44291 21.0461 6.19843 17.8016L5.13777 18.8622ZM18.9262 18.9262C22.7211 15.1313 22.6824 8.95796 18.8622 5.13777L17.8016 6.19843C21.0461 9.44291 21.0646 14.6664 17.8655 17.8655L18.9262 18.9262ZM5.07381 5.07381L3.80743 6.34019L4.86809 7.40085L6.13447 6.13447L5.07381 5.07381ZM4.33399 7.62051L6.87954 7.6333L6.88708 6.13332L4.34153 6.12053L4.33399 7.62051ZM5.08775 6.86675L5.07496 4.3212L3.57498 4.32874L3.58777 6.87429L5.08775 6.86675ZM2.3405 10.5812C1.93907 13.5099 2.87392 16.5984 5.13777 18.8622L6.19843 17.8016C4.27785 15.881 3.48663 13.2652 3.82661 10.7849L2.3405 10.5812Z"
                              fill="#555656"></path>
                          </g>
                        </svg></div>
                      <p class="sidebar-nav-item__title headline-3 mobile-heading-5">Log de compras</p><!---->
                    </a></li>
                </ul>
              </div>

              <div class="sidebar__group">
                <div class="sidebar__group-title caption-1">Ajuda</div>
                <ul class="sidebar__group-list">
                  <li><a href="/faq.php" class="sidebar-nav-item" data-soon-text="Em breve">
                      <div class="sidebar-nav-item__icon"><svg viewBox="0 0 28 28" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <g clip-path="url(#sidebar-faq-svg-clip)">
                            <path
                              d="M13.55 19.508a3.247 3.247 0 0 0-3.248 3.248 3.247 3.247 0 0 0 3.248 3.249 3.247 3.247 0 0 0 3.248-3.249 3.247 3.247 0 0 0-3.248-3.248Z"
                              fill="#555656"></path>
                            <path class="-hover"
                              d="M22.245 9.2c0 5.438-5.873 5.522-5.873 7.528V17c0 .623-.502 1.125-1.125 1.125h-3.399A1.122 1.122 0 0 1 10.723 17v-.46c0-2.896 2.199-4.054 3.858-4.987 1.425-.797 2.297-1.34 2.297-2.4 0-1.397-1.781-2.325-3.225-2.325-1.833 0-2.71.849-3.881 2.316a1.124 1.124 0 0 1-1.561.192L6.19 7.803a1.125 1.125 0 0 1-.244-1.542C7.855 3.533 10.278 2 14.019 2c3.98 0 8.226 3.108 8.226 7.2Z"
                              fill="#A8A9AB"></path>
                          </g>
                          <defs>
                            <clipPath id="sidebar-faq-svg-clip">
                              <path fill="#fff" transform="translate(5 2)" d="M0 0h18v24H0z"></path>
                            </clipPath>
                          </defs>
                        </svg></div>
                      <p class="sidebar-nav-item__title headline-3 mobile-heading-5">Perguntas Frequentes</p><!---->
                    </a></li>
                </ul>
                <ul class="sidebar__group-list">
                  <li><a href="/deslogar.php" class="sidebar-nav-item" data-soon-text="Em breve">
                      <div class="sidebar-nav-item__icon">
                        <svg width="28px" height="28px" viewBox="0 0 24 24" fill="none"
                          xmlns="http://www.w3.org/2000/svg" stroke="000000">
                          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                          <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                          <g id="SVGRepo_iconCarrier">
                            <path opacity="0.6"
                              d="M15 2H14C11.1716 2 9.75736 2 8.87868 2.87868C8 3.75736 8 5.17157 8 8V16C8 18.8284 8 20.2426 8.87868 21.1213C9.75736 22 11.1716 22 14 22H15C17.8284 22 19.2426 22 20.1213 21.1213C21 20.2426 21 18.8284 21 16V8C21 5.17157 21 3.75736 20.1213 2.87868C19.2426 2 17.8284 2 15 2Z"
                              fill="#ffffff"></path>
                            <path opacity="0.4"
                              d="M8 8C8 6.46249 8 5.34287 8.14114 4.5H8C5.64298 4.5 4.46447 4.5 3.73223 5.23223C3 5.96447 3 7.14298 3 9.5V14.5C3 16.857 3 18.0355 3.73223 18.7678C4.46447 19.5 5.64298 19.5 8 19.5H8.14114C8 18.6571 8 17.5375 8 16V12.75V11.25V8Z"
                              fill="#c5c5c5"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M4.46967 11.4697C4.17678 11.7626 4.17678 12.2374 4.46967 12.5303L6.46967 14.5303C6.76256 14.8232 7.23744 14.8232 7.53033 14.5303C7.82322 14.2374 7.82322 13.7626 7.53033 13.4697L6.81066 12.75L14 12.75C14.4142 12.75 14.75 12.4142 14.75 12C14.75 11.5858 14.4142 11.25 14 11.25L6.81066 11.25L7.53033 10.5303C7.82322 10.2374 7.82322 9.76256 7.53033 9.46967C7.23744 9.17678 6.76256 9.17678 6.46967 9.46967L4.46967 11.4697Z"
                              fill="#ffffff"></path>
                          </g>
                        </svg>
                      </div>
                      <p class="sidebar-nav-item__title headline-3 mobile-heading-5">Sair</p><!---->
                    </a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
          <div class="os-scrollbar-track os-scrollbar-track-off">
            <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
          </div>
        </div>
        <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-unusable os-scrollbar-auto-hidden">
          <div class="os-scrollbar-track os-scrollbar-track-off">
            <div class="os-scrollbar-handle" style="height: 100%; transform: translate(0px, 0px);"></div>
          </div>
        </div>
        <div class="os-scrollbar-corner"></div>
      </div>
    </div>

  </div>
</div>
<script>
  sidebarLayer = document.querySelector('.sidebar-layer')
  sidebarmobileOpen = document.querySelector('.sidebar-mobile-open')
  mobileBody = document.querySelector("body")
  sidebar = document.querySelector(".sidebar")

  document.querySelector('.sidebar-mobile-open').addEventListener('click', function () {
    sidebarLayer.classList.toggle('-visible')
    sidebarmobileOpen.classList.toggle('-open')
    sidebar.classList.toggle('-hidden')
    if (mobileBody.classList.contains('-scroll-lock')) {
      removerAtributos();
    } else {
      adicionarAtributos();
    }
  })

  function adicionarAtributos() {
    mobileBody.classList.toggle('-scroll-lock');
    mobileBody.setAttribute("data-scroll-lock-saved-overflow-y-property", "scroll");
    mobileBody.setAttribute("style", "overflow: hidden; padding-right: 0px;");
    mobileBody.setAttribute("data-scroll-lock-saved-inline-overflow-property", "");
    mobileBody.setAttribute("data-scroll-lock-saved-inline-overflow-y-property", "");
    mobileBody.setAttribute("data-scroll-lock-locked", "true");
    mobileBody.setAttribute("data-scroll-lock-filled-gap", "true");
    mobileBody.setAttribute("data-scroll-lock-current-fill-gap-method", "padding");
  }

  function removerAtributos() {
    mobileBody.classList.remove('-scroll-lock');
    mobileBody.removeAttribute("data-scroll-lock-saved-overflow-y-property");
    mobileBody.removeAttribute("style");
    mobileBody.removeAttribute("data-scroll-lock-saved-inline-overflow-property");
    mobileBody.removeAttribute("data-scroll-lock-saved-inline-overflow-y-property");
    mobileBody.removeAttribute("data-scroll-lock-locked");
    mobileBody.removeAttribute("data-scroll-lock-filled-gap");
    mobileBody.removeAttribute("data-scroll-lock-current-fill-gap-method");
  }
</script>
<script src="/assets/js/temas.js"></script>