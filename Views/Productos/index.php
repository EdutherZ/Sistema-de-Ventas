<?php

//VISTA PRINCIPAL
include "Views/Templates/Header.php";
include "Views/Templates/Aside.php";
?>

<main class="contenido">
    <h2 class="titulo-pag">Productos</h2>


    <div class="modal_text">
        <button class="modal_open" onclick="modalOpenPro(event);">Registrar </button>
    </div>

    <div class="prueba_resposive">
        <div class="table-inicio">

            <table id="tblProductos">
                <thead>
                    <tr>
                      
                        <th class="sticky">Código</th>
                        <th class="sticky">Nombre</th>
                        <th class="sticky">Descripción</th>
                        <th class="sticky">Precio</th>
                        <th class="sticky">Stock</th>
                        <th class="sticky">Categoría</th>
                        <th class="sticky">Estado</th>
                        <th class="sticky">Acciones</th>

                    </tr>
                </thead>
                <tbody class="body_table_usuarios">

                </tbody>

            </table>
        </div>
    </div>


    <section class="modal" id="modalPro">

        <!--ancho maximo dependiendo "frm_modal_v1 o frm_modal_v2" style="--ancho-maximo: 500px;" ideas locas-->
        <div class="modal__container" >
            <div class="cabecera_modal">
                <h3 id="title">Nuevo Cliente</h3>
                
            </div>

            <form method="post" id="frmProducto" class="frm-usuario">

                <!-- si se va a utilizar el precio venta 
                     se pueden usar el frm_modal_v1 -->
                <div class="frm_modal_v2" >

                    <div class="input-nom">
                        <label for="nombre">Nombre<span class="texto_rojo"> *</span></label> 
                        <input type="hidden" id="id" name="id">
                        <input id="nombre" type="text" name="nombre" placeholder="Nombre"> 

                    </div>
                    <div>
                        <label for="codigo">Código<span class="texto_rojo"> *</span></label> 
                        <input id="codigo" type="text" name="codigo" placeholder="Código" required> 
                    </div>
                    <div>
                        <label for="descripcion">Descripción<span class="texto_rojo"> *</span></label> 
                        <input id="descripcion" type="text" name="descripcion" placeholder="Descripción" required> 
                    </div>
                    <!--  Elprecio compra esta oculto y tiene un valor predefinido,
                            quitar todo esto cuando se vaya a utilizar   -->
                    <div class="d-none">
                        <label for="precio_compra">Precio de Compra<span class="texto_rojo"> *</span></label> 
                        <input value="1.00" id="precio_compra" type="text" name="precio_compra" placeholder="Precio de Compra" required> 
                    </div>
                    <div>
                        <label for="precio_venta">Precio de Venta ($)<span class="texto_rojo"> *</span></label> 
                        <input id="precio_venta" type="text" name="precio_venta" placeholder="Precio de Venta" required> 
                    </div>
                    <!--  selecion de la categoria-->
                    <div>
                        <label for="categoria">Categorías<span class="texto_rojo"> *</span></label> 
                        <select name="categoria" id="categoria">
                            <?php foreach ($data['categorias'] as $row) {    ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                            <?php } ?>
                        </select> 
                    </div>

                    <div class="text_end_modal">
                    <p><span class="texto_rojo"> *</span> Campos Obligatorios</p>
                    </div>
                </div>

              
                <div class="botons_modal">

                    <button type="button" class="modal_close" onclick="modalClosePro(event);">Cancelar</button>
                    <button type="button" class="modal_register" onclick="registerPro(event);" id="register_Pro">Registrar</button>
                </div>
            </form>

        </div>
    </section>



</main>
<?php
include "Views/Templates/Footer.php";
?>