<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">

    <div
    id="m_ver_menu"
    class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark "
    m-menu-vertical="1"
    m-menu-scrollable="0" m-menu-dropdown-timeout="500"
    >
    <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
      <li class="m-menu__section ">
        <h4 class="m-menu__section-text">
          Componentes
        </h4>
        <i class="m-menu__section-icon flaticon-more-v3"></i>
      </li>

      <?php
         if(
           ($this->help->tiene_permiso('Usuarios|index')) OR
           ($this->help->tiene_permiso('Controllers|index')) OR
           ($this->help->tiene_permiso('Usuarios|logueados')) OR
           ($this->help->tiene_permiso('Catalogo|index')) OR
           ($this->help->tiene_permiso('Usuarios|perfil')) OR
           ($this->help->tiene_permiso('Login|loginlogger'))
         )
         {
      ?>

      <li class="m-menu__item  m-menu__item--active" aria-haspopup="true"  m-menu-submenu-toggle="hover">
        <a  href="javascript:;" class="m-menu__link m-menu__toggle">
          <i class="m-menu__link-icon flaticon-layers"></i>
          <span class="m-menu__link-text">
            Framework
          </span>
          <i class="m-menu__ver-arrow la la-angle-right"></i>
        </a>
        <div class="m-menu__submenu ">
          <span class="m-menu__arrow"></span>
          <ul class="m-menu__subnav">

            <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
              <span class="m-menu__link">
                <span class="m-menu__link-text">
                  Framework
                </span>
              </span>
            </li>

            <?php if($this->help->tiene_permiso('Usuarios|perfil')){ ?>

            <li class="m-menu__item " aria-haspopup="true" >
              <a  href="javascript:;" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>usuarios/perfil');" class="m-menu__link ">
                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                  <span></span>
                </i>
                <span class="m-menu__link-text">
                  Mi perfil
                </span>
              </a>
            </li>

            <?php }if($this->help->tiene_permiso('Usuarios|index')){ ?>

            <li class="m-menu__item " aria-haspopup="true" >
              <a  href="javascript:;" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>usuarios');" class="m-menu__link ">
                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                  <span></span>
                </i>
                <span class="m-menu__link-text">
                  Control de usuarios
                </span>
              </a>
            </li>

            <?php }if($this->help->tiene_permiso('Controllers|index')){ ?>

            <li class="m-menu__item " aria-haspopup="true" >
              <a  href="javascript:;" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>controllers');" class="m-menu__link ">
                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                  <span></span>
                </i>
                <span class="m-menu__link-text">
                  Controladores
                </span>
              </a>
            </li>

            <?php }if($this->help->tiene_permiso('Usuarios|logueados')){ ?>

            <li class="m-menu__item " aria-haspopup="true" >
              <a  href="javascript:;" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>usuarios/logueados');" class="m-menu__link ">
                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                  <span></span>
                </i>
                <span class="m-menu__link-text">
                  Control de logins
                </span>
              </a>
            </li>

            <?php }if($this->help->tiene_permiso('Login|loginlogger')){ ?>

            <li class="m-menu__item " aria-haspopup="true" >
              <a  href="javascript:;" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>login/loginlogger');" class="m-menu__link ">
                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                  <span></span>
                </i>
                <span class="m-menu__link-text">
                  Registro de accesos
                </span>
              </a>
            </li>

            <?php }if($this->help->tiene_permiso('Catalogo|index')){ ?>

            <li class="m-menu__item " aria-haspopup="true" >
              <a  href="javascript:;" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>catalogo');" class="m-menu__link ">
                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                  <span></span>
                </i>
                <span class="m-menu__link-text">
                  Catálogo
                </span>
              </a>
            </li>
            <?php } ?>
          </ul>
        </div>
      </li>
      <?php } ?>
    </ul>
  </div>
  <!-- END: Aside Menu -->
</div>
<!-- END: Left Aside -->
