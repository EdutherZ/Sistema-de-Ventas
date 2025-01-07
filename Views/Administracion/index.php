<?php

//VISTA PRINCIPAL
include "Views/Templates/Header.php";
include "Views/Templates/Aside.php";
?>

<main class="contenido">

    <div class="prueba_resposive">
        <div class="titulo-pag">
            <h2 class="cabecera_stock">Datos de la Empresa</h2>
        </div>
        <form id="frmEmpresa">
            <div class="Compras_card">

                <div>
                    <input type="hidden" name="id" id="id" value="<?php echo $data['id'] ?>">
                    <label for="rif">RIF<span class="texto_rojo"> *</span></label>
                    <input type="text" name="rif" id="rif" placeholder="RIF" value="<?php echo $data['rif'] ?>">
                </div>
                <div class="max_width_empresa">

                    <label for="nombre">Nombre<span class="texto_rojo"> *</span></label>
                    <input type="text" name="nombre" id="nombre" placeholder="Nombre" value="<?php echo $data['nombre'] ?>">
                </div>
                <div>
                    <label for="telefono">Teléfono<span class="texto_rojo"> *</span></label>
                    <input type="text" name="telefono" id="telefono" placeholder="Teléfono" value="<?php echo $data['telefono'] ?>">
                </div>
                <div class="max_width_empresa">
                    <label for="direccion">Dirección<span class="texto_rojo"> *</span></label>
                    <input type="text" name="direccion" id="direccion" placeholder="Dirección" value="<?php echo $data['direccion'] ?>">
                </div>

                <div >
                    <label for="mensaje">Mensaje<span class="texto_rojo"> *</span></label>
                    <div>
                        <textarea class="form-control" name="mensaje" id="mensaje" rows="3" placeholder="Mensaje"><?php echo $data['mensaje'] ?></textarea>
                    </div>
                </div>
                <div class="button_datos_empresa">
                    <button class="compra_button" type="button" onclick="modificarEmpresa();">Modificar</button>
                </div>
                <div></div>
                <div></div>
                <div class="text_end_modal">
                    <p><span class="texto_rojo"> *</span> Campos Obligatorios</p>
                    </div>

            </div>
        </form>

    </div>









</main>
<?php
include "Views/Templates/Footer.php";
?>