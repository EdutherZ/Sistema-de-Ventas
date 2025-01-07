<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>	Iniciar Sesion</title>
    <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/style.css">
	<script src="<?php echo base_url; ?>Assets/js/all.js"></script>
	<script src="<?php echo base_url; ?>Assets/js/sweetalert2.all.min.js"></script>

</head>
<body>

<form class="login" id="frmLogin">
		<h2>Inicio de Sesión</h2>
		<div class="input_login" >
			<label for="email" > <i class="fa-solid fa-envelope"></i> Correo Electrónico</label><br>
			<input class="form-control py-4" id="email" name="email" type="email" placeholder="Correo Electrónico" required><br>

			<label for="clave"> <i class="fas fa-key"></i> Contraseña</label><br>
			<input class="form-control py-4"  id="clave" name="clave" type="password" placeholder="Contraseña" required><br>
		</div>
		<div class="alertaLogin" id="alerta"></div>

		<button type="submit" onclick="frmLogin(event);">Entrar</button>
		
		<div class="recuperar_clave">
			<span>¿Olvidó su contraseña?,<a href="Inicio/recuperar">Ingrese aquí</a></span>
		</div>
	</form>
	<script> 
		const base_url = "<?php echo base_url; ?>";
	</script>
	<script src="<?php echo base_url; ?>Assets/js/jquery-3.7.1.min.js" ></script>
	<script src="<?php echo base_url; ?>Assets/js/login.js"></script>
	
</body>
</html>