<footer class="footer" align="center">
            <p>© 2024 Distribuidora de Agua. Todos los derechos reservados.</p>
        </footer>



    </div>
    <section class="modal" id="cambiarPass">

        <div class="modal__container">
            <div class="cabecera_modal">
                <h3 id="title">Modificar Contraseña</h3>
              
            </div>

            <form id="frmCambiarPass" class="frm-usuario">

                <div class="frm_modal_v2">
                    <div>
                        <label for="clave_actual">Contraseña Actual<span class="texto_rojo"> (*)</span></label>  
                        
                        <input  id="clave_actual" type="password" name="clave_actual" placeholder="Contraseña Actual">  
                    </div>
                    <div>
                        <label for="nueva_clave">Nueva Contraseña<span class="texto_rojo"> (*)</span></label>  
                        <input id="nueva_clave" type="password" name="nueva_clave" placeholder="Nueva Contraseña">  

                    </div>
                    <div>
                        <label for="confirmar_clave">Confirmar Contraseña<span class="texto_rojo"> (*)</span></label>  
                        <input id="confirmar_clave" type="password" name="confirmar_clave" placeholder="Confirmar Contraseña">  

                    </div>
                    <div class="text_end_modal">
                    <p><span class="texto_rojo"> (*)</span> Campos Obligatorios</p>
                    </div>
                </div>

              
                <div class="botons_modal">
                <button type="button" class="modal_close" onclick="modalCloseClave(event);">Cancelar</button>

                    <button type="submit" class="modal_register" onclick="cambiarPass(event);" id="register_Clave">Modificar</button>
                </div>
            </form>

        </div>
    </section>
    <script src="<?php echo base_url; ?>Assets/js/jquery-3.7.1.min.js" >></script>
    <script src="<?php echo base_url; ?>Assets/js/jquery.dataTables.min.js" >></script>
    <script> 
		const base_url = "<?php echo base_url; ?>";
	</script>
    <script src="<?php echo base_url; ?>Assets/js/sweetalert2.all.min.js"></script>
    <script src="<?php echo base_url; ?>Assets/js/select2.min.js"></script>
    <!--<script src="<?php echo base_url; ?>Assets/js/chart.min.js"></script>-->
    <script src="<?php echo base_url; ?>Assets/js/funciones.js"></script>
</body>

</html>