//funcion para validar el login
function frmLogin(e) {
  e.preventDefault();
  const email = document.getElementById("email");
  const clave = document.getElementById("clave");
  const validEmail = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;

  //validacion del email para ver si es verdadero

  if (email.value == "") {
    clave.classList.remove("is-invalid");
    email.classList.add("is-invalid");
    email.focus();

    //validamos que no este vacio la clave
  } else if (!validEmail.test(email.value)) {
    alertas("Correo Electronico no valido", "warning");

    //validamos que no este vacio el email
  } else if (clave.value == "") {
    email.classList.remove("is-invalid");
    clave.classList.add("is-invalid");
    clave.focus();
  } else {
    //usamos ajax para validar el inicio de secion
    const url = base_url + "Inicio/validar";
    const frm = document.getElementById("frmLogin");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        //console.log(this.responseText);

        const res = JSON.parse(this.responseText);
        if (res == "ok") {
          //CARGAMOS LA VISTA
          window.location = base_url + "Inicio";
        } else {
          //MENSAJE DE CLAVE O EMAIL INVALIDO
          document.getElementById("alerta").classList.remove("d-none");
          alertas(res.msg, res.icono);
          clave.classList.remove("is-invalid");
          email.classList.remove("is-invalid");
        }
      }
    };
  }
}

function registrar(e) {
  e.preventDefault();

  const email = document.getElementById("email").value;
  const cedula = document.getElementById("cedula").value;
  const validEmail = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
  const validcedula = /^(?=.*[A-Za-z0-9]).{7,9}$/;

  if (email == "" || cedula == "") {
    alertas("Todos los campos son obligatorios", "warning");
  } else if (!validEmail.test(email)) {
    alertas("Correo electronico no valido", "warning");
  } else if (isNaN(cedula) || !validcedula.test(cedula)) {
    alertas("Cedula no valida", "warning");
  } else {
    document.getElementById("boton_recuperar_v").disabled = true;
    document.getElementById("boton_recuperar_r").disabled = true;

    //usamos ajax para validar el inicio de secion
    const url = base_url + "Inicio/recuperarPass";
    const frm = document.getElementById("frmRecuperar");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        //console.log(this.responseText);

        const res = JSON.parse(this.responseText);
        if (res == "ok") {
          alertas(
            "Cambio exitoso",
            "success",
            "La nueva contraseña fue enviada a su correo",
            3500
          );

          /* pueda que en algun momento me sirva recuperar.php
          document.getElementById("ocultar").classList.remove("d-none");
          document.getElementById("ocultar_boton2").classList.remove("d-none");
          document.getElementById("ocultar_boton").classList.add("d-none");
          document.getElementById("cedula").disabled = true;
          document.getElementById("email").disabled = true;*/
          document.getElementById("frmRecuperar").reset();
          document.getElementById("boton_recuperar_v").disabled = false;
          document.getElementById("boton_recuperar_r").disabled = false;

          // Establecer un temporizador para restablecer el estado del elemento oculto
          setTimeout(() => {
            window.location = "../";
          }, 3500);
        } else {
          alertas(res.msg, res.icono);
          document.getElementById("boton_recuperar_v").disabled = false;
          document.getElementById("boton_recuperar_r").disabled = false;
        }
      }
    };
  }
}

function alertas(msg, icono, texto = "", time = 3000) {
  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: time,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.onmouseenter = Swal.stopTimer;
      toast.onmouseleave = Swal.resumeTimer;
    },
  });
  Toast.fire({
    icon: icono,
    title: msg,
    text: texto,
  });
}
