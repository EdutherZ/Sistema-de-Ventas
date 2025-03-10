<?php

//VISTA PRINCIPAL
include "Views/Templates/Header.php";
include "Views/Templates/Aside.php";
?>

<main class="contenido">
    <h2 class="titulo-pag">Usuarios</h2>


    <div class="modal_text">
        <button class="modal_open" onclick="modalOpenUsu(event);" title="Registrar">Registrar </button>
    </div>


    <div class="prueba_resposive">
        <div class="table-inicio">
            <table id="tblUsuarios">
                <thead>
                    <tr>
                        <div class="cabecera">
                            <th class="sticky">Código</th>
                            <th class="sticky">Nombre</th>
                            <th class="sticky">Correo Electrónico</th>
                            <th class="sticky">Fecha de Registro</th>
                            <th class="sticky">Estado</th>
                            <th class="sticky">Acciones</th>
                        </div>
                    </tr>
                </thead>
                <tbody class="body_table_usuarios">

                </tbody>

            </table>
        </div>
    </div>


    <section class="modal" id="modalusu">

        <!--ancho maximo dependiendo "frm_modal_v1 o frm_modal_v2" style="--ancho-maximo: 600px;"  ideas locas -->
        <div class="modal__container">
            <div class="cabecera_modal">
                <h3 id="title">Nuevo Usuario</h3>

            </div>

            <form method="post" id="frmUsuario" class="frm-usuario">


                <div class="frm_modal_v1">
                    <div>
                        <label for="nombres">Nombre<span class="texto_rojo"> *</span></label>
                        <input type="hidden" id="id" name="id">
                        <input id="nombres" type="text" name="nombres" placeholder="Nombre" pattern="[a-zA-Z ]{2,254}" required>
                    </div>
                    <div>
                        <label for="apellidos">Apellido<span class="texto_rojo"> *</span></label>
                        <input id="apellidos" type="text" name="apellidos" placeholder="Apellido" required>
                    </div>
                    <div>
                        <label for="cedula">Cédula<span class="texto_rojo"> *</span></label>
                        <input id="cedula" type="text" name="cedula" placeholder="Cédula" required>
                    </div>
                    <div>
                        <label for="email">Correo Electrónico<span class="texto_rojo"> *</span></label>
                        <input id="email" type="email" name="email" placeholder="Correo Electrónico" required>
                    </div>
                    <div class="max_width_usuario">
                        <label for="direccion">Dirección<span class="texto_rojo"> *</span></label>
                        <input id="direccion" type="text" name="direccion" placeholder="Dirección" required>
                    </div>
                    <div>
                        <label for="nombre">Teléfono<span class="texto_rojo"> *</span></label>
                        <input id="telefono" type="text" name="telefono" placeholder="Teléfono" required>
                    </div>

                    <div>
                        <label for="nombre">Usuario<span class="texto_rojo"> *</span></label>
                        <input id="usuario" type="text" name="usuario" placeholder="Usuario" required>
                    </div>

                    <div id="claves_contra">
                        <label for="email">Contraseña<span class="texto_rojo"> *</span></label>
                        <input id="clave" type="password" name="clave" placeholder="Contraseña" required>
                        <div id="insegura">
                            <span id="clave_insegura"></span>
                        </div>
                    </div>
                    <div id="claves_confir">
                        <label for="email">Confirmar Contraseña<span class="texto_rojo"> *</span></label>
                        <input id="confirmar" type="password" name="confirmar" placeholder="Confirmar Contraseña" required>
                    </div>
                    
                    <div class="text_end_modal">
                    <p><span class="texto_rojo"> *</span> Campos Obligatorios</p>
                    </div>
                </div>


                <div class="botons_modal">


                    <button type="button" class="modal_close" onclick="modalCloseUsu(event);">Cancelar</button>
                    <button type="button" class="modal_register" onclick="registerUser(event);" id="registerUsu">Registrar</button>
                </div>
            </form>

        </div>
    </section>



</main>
<?php
include "Views/Templates/Footer.php";
?>