<?php

//VISTA PRINCIPAL
include "Views/Templates/Header.php";
include "Views/Templates/Aside.php";
?>

<main class="contenido">
    <h2 class="titulo-pag">Categorias</h2>


    <div class="modal_text">
        <button class="modal_open" onclick="modalOpenCat(event);">Registrar </button>
    </div>


    <div class="prueba_resposive">
        <div class="table-inicio">
            <table id="tblCategorias">
                <thead>
                    <tr>
                        <th class="sticky">Id</th>
                        <th class="sticky">Código</th>
                        <th class="sticky">Nombre</th>
                        <th class="sticky">IVA</th>
                        <th class="sticky">Estado</th>
                        <th class="sticky">Acciones</th>

                    </tr>
                </thead>
                <tbody class="body_table_usuarios">
                    <tr>

                    </tr>
                </tbody>

            </table>
        </div>
    </div>


    <section class="modal" id="modalCat">

        <div class="modal__container">
            <div class="cabecera_modal">
                <h3 id="title">Nuevo Cliente</h3>
              
            </div>

            <form method="post" id="frmCategotoria" class="frm-usuario">

                <div class="frm_modal_v2">
                    <div class="d-none">
                        <label for="codigo">Código<span class="texto_rojo"> *</span></label>  
                        <input title="Auto-Generado" id="codigo" type="text" name="codigo" placeholder="Código" disabled>  
                    </div>
                    <div>
                        <label for="nombre">Nombre<span class="texto_rojo"> *</span></label>  
                        <input type="hidden" id="id" name="id">
                        <input id="nombre" type="text" name="nombre" placeholder="Nombre">  

                    </div>
                    <div>
                        <label for="nombre">Impuesto (IVA)<span class="texto_rojo"> *</span></label>  
                        <select name="iva" id="iva" required>
                            <option value="Exento">Exento</option>
                            <option value="Aplica">Aplica</option>
                        </select>
                    </div>

                    <div class="text_end_modal">
                    <p><span class="texto_rojo"> *</span> Campos Obligatorios</p>
                    </div>
                </div>

              
                <div class="botons_modal">

                    <button type="button" class="modal_close" onclick="modalCloseCat(event);">Cancelar</button>
                    <button type="button" class="modal_register" onclick="registerCat(event);" id="register_Cat">Registrar</button>
                </div>
            </form>

        </div>
    </section>



</main>
<?php
include "Views/Templates/Footer.php";
?>