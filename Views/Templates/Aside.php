<!-- 
            <div class="usuario" align="center">
                <h2>Menú</h2>
                <img src="<?php echo base_url; ?>Assets\fotos\ava" width="175px" align="center" alt="" width="175px" align="center">
                <h3>Usuario</h3><br><br>
                <a href="<?php echo base_url; ?>Usuarios/salir">Cerrar Sesion</a>

               
            </div>
            <div class="funciones">
                <table>
                    <tr><a href="<?php echo base_url; ?>" align="center">Inicio</a></tr>
                    <tr>

                    <details name="info">
                            <summary><i class="fas fa-tools"style="font-size: 23px; color: white;"></i> Configuracion </summary>
                            <hr>

                            <a href="<?php echo base_url; ?>Usuarios"><i class="fas fa-user"style="font-size: 23px; color: white;"></i> Usuarios</a>

                            <a href="Eliminar Vendedor">Bitacora</a>
                            <hr>
                        </details>

                    </tr>
                    <tr>

                        <a href="<?php echo base_url; ?>Clientes" class="modulos"><i class="fas fa-users"style="font-size: 23px; color: white;"></i> Clientes</a>


                    </tr>
                    <tr>
                        <details name="info">
                            <summary>Ventas </summary>
                            <hr>

                            <a href="Registro de Vendedor">Consultar Ventas</a>

                            <a href="Eliminar Vendedor">Pagos</a>
                            <hr>
                        </details>
                    </tr>
                    <tr>

                        <a href="Registro de Vendedor" class="modulos">Tasas</a>

                    </tr>

                </table>
            </div>
-->

<aside class="sidebar">



    <div class="usuario" align="center">
        <h2>Menú</h2>
        <div class="img_user">
            <img src="<?php echo base_url; ?>Assets/img/image.webp" height="95px" ><br>

            <div class="user_name">
                <h3><?php echo $user_name ?></h3>

             

            </div>

            <!--<ul>
            <a title="Salir" href="<?php echo base_url; ?>Usuarios/salir" class="login_out" onclick="cerrarSesion();"><i class="fa-solid fa-right-from-bracket"style="font-size: 23px; color: white;"></i></a>
        </ul>-->

        </div>



        <ul class="list">

            <li class="list__item">
                <div class="list__button">
                    <i class="fa-solid fa-house " style="font-size: 23px; color: white;"></i>
                    <a href="<?php echo base_url; ?>" class="nav__link">Inicio</a>
                </div>
            </li>

            <li class="list__item list__item--click">

                <div class="list__button list__button--click">
                    <i class=" fa-solid fa-gear" style="font-size: 23px; color: white;"></i>
                    <a class="nav__link">Configuración</a>
                    <!--<img src="assets/arrow.svg" class="list__arrow">-->
                    <i class="fa-solid fa-chevron-right" style="color: white;"></i>
                </div>

                <ul class="list__show">
                    <li class="list__inside">
                        <a onclick="modalOpenClave(event);" class="nav__link nav__link--inside alinear_texto"><i class="fa fa-users" style=" color: white;"></i> Cambiar <br>&nbsp;&nbsp;&nbsp;&nbsp; Contraseña</a>
                    </li>


                    <li class="list__inside">
                        <a href="<?php echo base_url; ?>Administracion" class="nav__link nav__link--inside"><i class="fas fa-tools" style=" color: white;"></i> Empresa</a>
                    </li>
                </ul>

            </li>

            <li class="list__item list__item--click">

                <div class="list__button list__button--click">
                    <i class="fa fa-users " style="font-size: 23px; color: white;"></i>
                    <a class="nav__link">Usuarios</a>
                    <!--<img src="assets/arrow.svg" class="list__arrow">-->
                    <i class="fa-solid fa-chevron-right" style="color: white;"></i>
                </div>

                <ul class="list__show">
                    <li class="list__inside">
                        <a href="<?php echo base_url; ?>Usuarios" class="nav__link nav__link--inside"><i class="fa fa-user" style=" color: white;"></i> Registro</a>
                    </li>

                    <li class="list__inside">
                        <a href="" class="nav__link nav__link--inside"><i class="fa-solid fa-timeline" style=" color: white;"></i> Bitácora</a>
                    </li>

                </ul>

            </li>


            <li class="list__item">
                <div class="list__button">
                    <i class="fas fa-users" style="font-size: 23px; color: white;"></i>
                    <a href="<?php echo base_url; ?>Clientes" class="nav__link">Clientes</a>
                </div>
            </li>

            <li class="list__item list__item--click">

                <div class="list__button list__button--click">
                    <i class="fa fa-users" style="font-size: 23px; color: white;"></i>
                    <a class="nav__link">Productos</a>
                    <!--<img src="assets/arrow.svg" class="list__arrow">-->
                    <i class="fa-solid fa-chevron-right" style="color: white;"></i>
                </div>

                <ul class="list__show">
                    <li class="list__inside">
                        <a href="<?php echo base_url; ?>Productos" class="nav__link nav__link--inside"><i class="fa fa-users" style="color: white;"></i> Productos</a>
                    </li>

                    <li class="list__inside">
                        <a href="<?php echo base_url; ?>Categorias" class="nav__link nav__link--inside"><i class="fa fa-users" style="color: white;"></i> Categorías</a>

                </ul>
            </li>

            <li class="list__item list__item--click">

                <div class="list__button list__button--click">
                    <i class="fa fa-user" style="font-size: 23px; color: white;"></i>
                    <a class="nav__link">Stock</a>
                    <!--<img src="assets/arrow.svg" class="list__arrow">-->
                    <i class="fa-solid fa-chevron-right" style="color: white;"></i>
                </div>

                <ul class="list__show">
                    <li class="list__inside">
                        <a href="<?php echo base_url; ?>Compras" class="nav__link nav__link--inside"><i class="fa fa-tools" style=" color: white;"></i> Stock</a>
                    </li>

                    <li class="list__inside">
                        <a href="<?php echo base_url; ?>Compras/historial" class="nav__link nav__link--inside"><i class="fa fa-tools" style=" color: white;"></i> Historial</a>
                    </li>
                </ul>
            </li>



            <li class="list__item list__item--click">
                <div class="list__button list__button--click">
                    <i class="fa-solid fa-tags" style="font-size: 23px; color: white;"></i>
                    <a class="nav__link">Ventas</a>
                    <i class="fa-solid fa-chevron-right" style="color: white;"></i>
                </div>

                <ul class="list__show">
                    <li class="list__inside">
                        <a href="<?php echo base_url; ?>Compras/ventas" class="nav__link nav__link--inside"><i class="fa-solid fa-square-poll-horizontal" style=" color: white;"></i> Ventas</a>
                    </li>

                    <li class="list__inside">
                        <a href="<?php echo base_url; ?>Compras/historial_ventas" class="nav__link nav__link--inside"><i class="fa-solid fa-cash-register" style=" color: white;"></i> Historial</a>
                    </li>

                </ul>

            </li>


            <li class="list__item">
                <div class="list__button">
                <i class="fa-solid fa-coins" style="font-size: 23px; color: white;"></i>
                    <a href="<?php echo base_url; ?>Tasas" class="nav__link">Tasas</a>
                </div>
            </li>

            <li class="list__item">
                <div class="list__button">
                    <i class="fa-solid fa-file-lines" style="font-size: 23px; color: white;"></i>
                    <a href="" class="nav__link">Reportes</a>
                </div>
            </li>

            

            <li class="list__item">
                <div class="list__button">
                    <i class="fa-solid fa-right-from-bracket" style="font-size: 23px; color: white;"></i>
                    <a title="Salir" href="<?php echo base_url; ?>Usuarios/salir" class=" nav__link" onclick="cerrarSesion();">Salir</a>

                </div>
            </li>
        </ul>


        <script>
            // Asignar la función de cerrar sesión al botón
            function cerrarSesion() {
                sessionStorage.removeItem('primeraVisita');
                //alert('Has cerrado sesión. La próxima vez que entres, verás el mensaje de bienvenida.');
            }
        </script>

</aside>