<?php

//VISTA PRINCIPAL
include "Views/Templates/Header.php";
include "Views/Templates/Aside.php";
?>


<main class="contenido">
    <div>
    <h2 class="titulo-pag" id="bienvenida_sistem"></h2>
    </div>
<div class="prueba_resposive">

<div class="contenedor_widgets">

    <div class="widget_usuarios">
        <div class="cabecera_widget">
            Usuarios
            <i class="fas fa-user fa-2x"></i>
        </div>
        
        <div class="cuerpo_widget">
            <a href="<?php echo base_url; ?>Usuarios">Ver Detalles</a>
            <span><?php echo $data['usuarios']['total'] ?></span>
        </div>
    </div>
    

    <div class="widget_clientes">
        <div class="cabecera_widget">
        Clientes
            <i class="fas fa-users fa-2x"></i>
        </div>
        
        <div class="cuerpo_widget">
            <a href="<?php echo base_url; ?>Clientes">Ver Detalles</a>
            <span><?php echo $data['clientes']['total'] ?></span>        </div>
    </div>

    <div class="widget_productos">
        <div class="cabecera_widget">
            Productos
            <i class="fab fa-product-hunt fa-2x"></i>
        </div>
        
        <div class="cuerpo_widget">
            <a href="<?php echo base_url; ?>Productos">Ver Detalles</a>
            <span><?php echo $data['productos']['total'] ?></span>        </div>
        
    </div>

    <div class="widget_ventas">
        <div class="cabecera_widget">
            Ventas del Día
            <i class="fas fa-cash-register fa-2x"></i>
        </div>
        
        <div class="cuerpo_widget">
            <a href="<?php echo base_url; ?>Compras/historial_ventas">Ver Detalles</a>
            <span><?php echo $data['ventas']['total'] ?></span>        </div>
        
    </div>

    <div class="widget_tasa">
        <div class="cabecera_widget">
            Tasa del Día ($)
            <i class="fa-solid fa-coins fa-2x"></i>
        </div>
        
        <div class="cuerpo_widget">
            <a href="<?php echo base_url; ?>Tasas">Ver Detalles</a>
            <span><?php echo $data['tasa']['dolar'] ?> Bs</span>
        </div>
    </div>
    
</div>
</div>
    <script>
        // Función para mostrar el mensaje de bienvenida
        function mostrarMensaje() {
            const mensajeDiv = document.getElementById('bienvenida_sistem');
            const primeraVisita = sessionStorage.getItem('primeraVisita');

            const ahora = new Date();
            const horaLocal = ahora.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });

            let saludo_hora;
            if (horaLocal >= '04:00' && horaLocal < '12:00') {
                saludo_hora = "buenos días";
            } else if (horaLocal >= '12:00' && horaLocal < '19:00') {
                saludo_hora = "buenas tardes";
            } else {
                saludo_hora = "buenas noches";
            }

            if (!primeraVisita) {

                mensajeDiv.textContent = '¡Hola, ' + saludo_hora + '! <?php echo $user_name ?>, ¡Bienvenido al sistema!';
                sessionStorage.setItem('primeraVisita', 'no');
            } else {
                mensajeDiv.textContent = '¡Hola de nuevo! <?php echo $user_name ?>, ¿Que quieres hacer hoy?';
            }
        }

        // Mostrar el mensaje al cargar la página
        window.onload = mostrarMensaje;
    </script>

</main>

<?php
include "Views/Templates/Footer.php";
?>