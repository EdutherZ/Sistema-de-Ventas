<?php

//VISTA PRINCIPAL
include "Views/Templates/Header.php";
include "Views/Templates/Aside.php";
?>

<main class="contenido">

    <div class="prueba_resposive">
        <div class="titulo-pag">
            <h2 class="cabecera_stock">Tasa del Día</h2>
        </div>
        <form id="frmTasas">
            <div class="Compras_card">
                
                <div class="currency-input">
                    <input type="hidden" name="id" id="id" value="<?php echo $data['id'] ?>">
                    <label for="dolar">Dólar<span class="texto_rojo"> *</span></label>
                    <input type="text" name="dolar" id="dolar" placeholder="Dólar" value="<?php echo $data['dolar'] ?>">
                </div>
                <div class="currency-input">

                    <label for="euro">Euro<span class="texto_rojo"> *</span></label>
                    <input type="text" name="euro" id="euro" placeholder="Euro" value="<?php echo $data['euro'] ?>">
                </div>
                
                <div class="button_datos_tasas">
                    <button class="compra_button" type="button" onclick="modificarTasas();">Modificar</button>
                </div>
                <div>
                    
                </div>
                <div class="text_end_modal">
                    <p><span class="texto_rojo"> *</span> Campos Obligatorios</p>
                    </div>

            </div>
        </form>


        <div class="container_tasas">
        <div class="table-inicio">
            
            <table id="tblTasas" >
                <thead >
                    <tr>
                        <div class="cabecera">
                            <th class="sticky">Id</th>
                            <th class="sticky">Valor del Dólar</th>
                            <th class="sticky">Valor del Euro</th>
                            <th class="sticky">Fecha de Registro</th>
                    
                        </div>
                    </tr>
                </thead>
                <tbody class="body_table_tasas">

                </tbody>

            </table>
          
        </div>
    </div>
    </div>


</main>
<?php
include "Views/Templates/Footer.php";
?>