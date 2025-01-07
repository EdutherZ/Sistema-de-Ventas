<?php

//VISTA PRINCIPAL
include "Views/Templates/Header.php";
include "Views/Templates/Aside.php";
?>

<main class="contenido">

    <div class="prueba_resposive">
        <div class="titulo-pag cabecera_stock">

            <h3>Nuevo Ingreso</h3>

        </div>

        <form id="frmCompra">

            <div class="Compras_card">
                <div class="text_top_compras">
                    <p><b>Precione (Enter) para seleccionar un producto o agregar una cantidad</b></p>
                </div>

                <div class="cliente_venta">
                    <input type="hidden" name="id" id="id">
                    <label for="codigo">Código de Barras</label>
                    <select name="prod" id="prod_comp">
                        <option value=""></option>
                        <?php foreach ($data['prod'] as $row) { ?>
                            <option value="<?php echo $row['id']; ?>" data-codigo="<?php echo $row['codigo']; ?>">
                                <?php echo $row['nombre']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="name_stock">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" placeholder="Nombre del Producto" disabled>
                </div>
                <div class="descrip_stock">
                    <label for="descripcion">Descripción</label>
                    <input type="text" name="descripcion" id="descrip" placeholder="Descripción del Producto" disabled>
                </div>
                <div class="cantidad_stock">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" name="cantidad" id="cantidad" onkeypress="return preventDot(event);" onkeyup="calcularPrecio(event);">
                </div>
                <!-- oculto los elementos con la clase d-none -->
                <div class="d-none">
                    <labe class="precio_stock" for="precio_compra">Precio</label>
                        <input type="text" name="precio_compra" id="precio_compra" placeholder="Precio de Ingreso" disabled>
                </div>
                <div class="sub_total_stock d-none">
                    <label for="sub_total">Sub Total</label>
                    <input type="text" name="sub_total" id="sub_total" placeholder="Sub Total" disabled>
                </div>

            </div>
        </form>
        <!--
        <div class="Compras_card2">
            <div class="sub_stock">
                <label for="precio">Sub Total</label>
                <input type="number" name="precio" id="precio" placeholder="" disabled>
            </div>
        </div>
         -->

        <div class="table-inicio">
            <div class="tabla_compra">
                <table>
                    <thead>
                        <tr>

                            <th class="table_id">N°</th>
                            <th class="sticky">Nombre</th>
                            <th class="sticky">Descripción</th>
                            <th class="sticky">Cantidad</th>
                            <th class="sticky">Precio</th>
                            <th class="sticky">Sub-Total</th>
                            <th class="sticky">Acciones</th>

                        </tr>
                    </thead>
                    <tbody class="body_table_usuarios" id="tblDetalle">

                    </tbody>

                </table>
            </div>
        </div>

        <div class="card_stock_total">
            <div class="total_stock d-none">
                <label for="total">Total a Pagar</label>
                <input type="text" name="total" id="total" placeholder="Total" disabled>
            </div>

            <div id="boton_compra">
                <button  class="compra_button" type="button" onclick="procesar(1);">Generar Ingreso</button>
            </div>
        </div>



    </div>

</main>
<?php
include "Views/Templates/Footer.php";
?>