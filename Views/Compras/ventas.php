<?php

//VISTA PRINCIPAL
include "Views/Templates/Header.php";
include "Views/Templates/Aside.php";
?>

<main class="contenido">


    <div class="prueba_resposive">
        <div class="titulo-pag">
            <h3 class="cabecera_stock">Nueva Venta</h3>
        </div>
        <form id="frmVenta">
            <div class="Compras_card">
                <div class="text_top_compras">
                    <p><b>Precione (Enter) para seleccionar un producto o agregar una cantidad</b></p>
                </div>


                <div class="cliente_venta">
                    <input type="hidden" name="id" id="id">
                    <label for="codigo">Código de Barras</label>
                    <select name="prod" id="prod">
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
                    <input type="number" name="cantidad" id="cantidad" onkeypress="return preventDot(event);" onkeyup="calcularPrecioVenta(event);">
                </div>
                <!-- oculto los elementos con la clase d-none -->
                <div>
                    <labe class="precio_stock" for="precio_venta">Precio ($)</label>
                        <input type="text" name="precio_venta" id="precio_venta" placeholder="Precio de Venta" disabled>
                </div>
                <div>
                    <labe class="precio_stock" for="precio_venta_bs">Precio (Bs)</label>
                        <input type="text" name="precio_venta_bs" id="precio_venta_bs" placeholder="Precio de Venta" disabled>
                </div>
                <div class="sub_total_stock">
                    <label for="sub_total">Sub Total (Bs)</label>
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
                            <th class="sticky">Precio ($)</th>
                            <th class="sticky">Sub-Total ($)</th>
                            <th class="sticky">Sub-Total (Bs)</th>
                            <th class="sticky">Acciones</th>

                        </tr>
                    </thead>
                    <tbody class="body_table_usuarios" id="tblDetalleVenta">

                    </tbody>

                </table>
            </div>
        </div>

        <div class="card_stock_total">

            <div>
                <div class="total_stock">
                    <label for="total_bs">Total a Pagar (Bs)</label>
                    <input type="text" name="total_bs" id="total_bs" placeholder="Total" disabled>
                </div>

                <div id="boton_venta">
                    <button class="compra_button" type="button" onclick="procesar(2);">Generar Venta</button>
                </div>
            </div>
            <div class="total_stock">
                <label for="total">Total a Pagar ($)</label>
                <input type="text" name="total" id="total" placeholder="Total" disabled>
            </div>



            <div class="cliente_venta">
                <label for="cliente"><i class="fas fa-users"></i> Buscar Cliente</label><br>
                <select name="cliente" id="cliente">
                    <?php foreach ($data['clientes'] as $row) { ?>
                        <option value="<?php echo $row['id']; ?>" data-cedula="<?php echo $row['cedula']; ?>">
                            <?php echo $row['nombres'] . ' ' . $row['apellidos']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>


        </div>

    </div>

</main>
<?php
include "Views/Templates/Footer.php";
?>