<?php

//VISTA PRINCIPAL
include "Views/Templates/Header.php";
include "Views/Templates/Aside.php";
?>

<main class="contenido">
    <h2 class="titulo-pag">Clientes</h2>


    <div class="modal_text">
        <button class="modal_open" onclick="modalOpenCli(event);">Registrar </button>
    </div>

    <div class="prueba_resposive">
        <div class="table-inicio">
            <table id="tblClientes">
                <thead>
                    <tr>

                        <th class="sticky">Nombre</th>
                        <th class="sticky">Cédula</th>
                        <th class="sticky">Correo Electrónico</th>
                        <th class="sticky">Teléfono</th>
                        <th class="sticky">Estado</th>
                        <th class="sticky">Acciones</th>

                    </tr>
                </thead>
                <tbody class="body_table_usuarios">

                </tbody>

            </table>
        </div>
    </div>


    <section class="modal" id="modalCli">

        <div class="modal__container">
            <div class="cabecera_modal">
                <h3 id="title">Nuevo Cliente</h3>

            </div>

            <form method="post" id="frmCliente" class="frm-usuario">

                <div class="frm_modal_v1">

                    <div>
                        <label for="nombres">Nombre<span class="texto_rojo"> *</span></label>
                        <input type="hidden" id="id" name="id">
                        <input id="nombres" type="text" name="nombres" placeholder="Nombre">
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
                    <div>
                        <label for="telefono">Teléfono<span class="texto_rojo"> *</span></label>
                        <input id="telefono" type="text" name="telefono" placeholder="Teléfono" required>
                    </div>
                   <div>
                        <label for="direccion">Dirección<span class="texto_rojo"> *</span></label>
                        <input id="direccion" type="text" name="direccion" placeholder="dirección">
                    </div>

                    <div class="text_end_modal">
                    <p><span class="texto_rojo"> *</span> Campos Obligatorios</p>
                    </div>

                </div>

                <div class="botons_modal">

                    <button type="button" class="modal_close" onclick="modalCloseCli(event);">Cancelar</button>
                    <button type="button" class="modal_register" onclick="registerCli(event);" id="register_Cli">Registrar</button>
                </div>
            </form>

        </div>
    </section>

    <!-- detalles del cliente-->

    <section class="modal" id="modalCliDetalles">

        <div class="modal__container">
            <div class="cabecera_modal">
                <h3 id="titled">Nuevo Cliente</h3>

            </div>

            <form method="post"  class="frm_Detalle">

                <div class="frm_modal_v1">

                    <div>
                        <label for="nombres">Nombre </label>
                        <input type="hidden" id="id2" name="id">
                        <input id="nombress" type="text" name="nombres" placeholder="Nombre">
                    </div>
                    <div>
                        <label for="apellidos">Apellido </label>
                        <input id="apellidoss" type="text" name="apellidos" placeholder="Apellido" required>
                    </div>
                    <div>

                        <label for="cedula">Cédula </label>
                        <input id="cedulas" type="text" name="cedula" placeholder="Cédula" required>
                    </div>
                    <div>
                        <label for="email">Correo Electrónico </label>
                        <input id="emails" type="email" name="email" placeholder="Correo Electrónico" required>
                    </div>
                    <div>
                        <label for="telefono">Teléfono </label>
                        <input id="telefonos" type="text" name="telefono" placeholder="Teléfono" required>
                    </div>

                    <div>
                        <label for="direccion">Dirección </label>
                        <input id="direccions" type="text" name="direccion" placeholder="dirección">
                    </div>
                    <div class="d-none">
                        <label for="direccion">Registro </label>
                        <input id="fech_reg" type="text" name="direccion" placeholder="direccion">
                    </div>
                    <div class="d-none">
                        <label for="direccion">Actualización </label>
                        <input id="fech_act" type="text" name="direccion" placeholder="direccion">
                    </div>

                    
                </div>

                <div class="botons_modal">

                    <button align="center" type="button" class="modal_close" onclick="modalCloseCli(event);">Cerrar</button>
                </div>
            </form>

        </div>
    </section>


</main>
<?php
include "Views/Templates/Footer.php";
?>