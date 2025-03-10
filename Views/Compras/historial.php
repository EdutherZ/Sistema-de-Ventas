<?php

//VISTA PRINCIPAL
include "Views/Templates/Header.php";
include "Views/Templates/Aside.php";
?>

<main class="contenido">
    <h2 class="titulo-pag">Historial de Ingresos</h2>
    <br><br>


    <div class="prueba_resposive">
        <div class="table-inicio">
            <div class="tabla_">
                <table id="t_historial_c">
                    <thead>
                        <tr>

                            <th class="sticky">Id</th>
                            <th class="sticky">Total</th>
                            <th class="sticky">Fecha Compra</th>
                            <th class="sticky">Estado</th>
                            <th class="sticky">Acciones</th>

                        </tr>
                    </thead>
                    <tbody class="body_table_usuarios">

                    </tbody>

                </table>
            </div>
        </div>
    </div>



</main>
<?php
include "Views/Templates/Footer.php";
?>