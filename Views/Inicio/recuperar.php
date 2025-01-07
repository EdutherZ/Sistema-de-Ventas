<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/style.css">
    <script src="<?php echo base_url; ?>Assets/js/all.js" ></script>
    <script src="<?php echo base_url; ?>Assets/js/sweetalert2.all.min.js"></script>
    <script>
        const base_url = "<?php echo base_url; ?>";
    </script>
</head>

<body>

    <form method="post" class="login_recuperar" id="frmRecuperar">

        <h3 class="titulo-pag" align="center">Recuperar Contraseña</h3>

        <div class="frm_login_recuperar">


            <div>
                <label for="email">Correo Eletrónico</label>
                <input id="email" type="text" name="email" placeholder="Correo Eletrónico" required>
            </div>
            <div>
                <label for="cedula">Cédula</label>
                <input id="cedula" type="text" name="cedula" placeholder="Cédula" required>
            </div>
<!--pueda que en algun momento me sirva login.js
            
            <div id="ocultar">
                <div>

                    <label for="nueva_clave">Nueva Contraseña</label>
                    <input id="nueva_clave" type="password" name="nueva_clave" placeholder="Nueva Contraseña" required>
                </div>
                <div>
                    <label for="confirmar_clave">Confirmar Contraseña</label>
                    <input id="confirmar_clave" type="password" name="confirmar_clave" placeholder="Confirmar Contraseña" required>
                </div>
            </div>

        </div>

        <div class="botons_modal">
            <div id="ocultar_boton3">
            <button type="button" class="modal_close" onclick="window.location = '../';">Volver</button>
            </div>
            <div id="ocultar_boton">
                <button type="button" class="modal_register" onclick="verificar(event);">Verificar</button>
            </div>
            <div id="ocultar_boton2">
                <button type="button" class="modal_register" onclick="registerCli(event);">Registrar</button>
            </div>
        </div>
    </form>

    <script>
        document.getElementById("ocultar").classList.add("d-none");
        document.getElementById("ocultar_boton2").classList.add("d-none");
    </script>
-->
        </div>
        <div class="botons_modal">

            <button style="width: 120px;" id="boton_recuperar_v" type="button" class="modal_close" onclick="window.location = '../';">Volver</button>

            <button style="width: 120px;" id="boton_recuperar_r" type="button" class="modal_register" onclick="registrar(event);">Enviar</button>
        </div>
    </form>
    <script>

        document.getElementById("frmRecuperar").reset();
    </script>

    <script src="<?php echo base_url; ?>Assets/js/jquery-3.7.1.min.js"></script>
    <script src="<?php echo base_url; ?>Assets/js/login.js"></script>

</body>

</html>