//**********codigo para el menu desplegable del sistema*/
const listElements = document.querySelectorAll(".list__button--click");

listElements.forEach((listElement) => {
  listElement.addEventListener("click", () => {
    // Cerrar todos los submenús abiertos
    listElements.forEach((otherElement) => {
      if (otherElement !== listElement) {
        const otherMenu = otherElement.nextElementSibling;
        otherMenu.style.height = "0";
        otherElement.classList.remove("active");
        otherElement.classList.remove("arrow"); // Quita la clase .arrow
      }
    });

    listElement.classList.toggle("arrow"); // Agrega o quita la clase .arrow
    listElement.classList.toggle("active");

    const menu = listElement.nextElementSibling;
    if (menu.clientHeight === 0) {
      menu.style.height = `${menu.scrollHeight}px`;
    } else {
      menu.style.height = "0";
    }
  });
});

//**********************DATATABLE *************/

//************************************** */
let cod;
let tblUsuarios,
  tblClientes,
  tblProductos,
  tblCategorias,
  t_historial_c,
  t_historial_v,
  tblTasas;
// TODAS LAS TABLAS DE DATATABLE
document.addEventListener("DOMContentLoaded", function () {
  function initializeSelect2(selector, dataAttribute) {
    $(selector).select2({
      theme: "classic",
      width: "90%",
      matcher: function (params, data) {
        if ($.trim(params.term) === "") {
          return data;
        }

        const attributeValue = $(data.element).data(dataAttribute);
        if (
          data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1 ||
          (attributeValue &&
            attributeValue.toString().indexOf(params.term) > -1)
        ) {
          return data;
        }

        return null;
      },
    });
  }

  initializeSelect2("#cliente", "cedula");
  initializeSelect2("#prod_comp", "codigo");
  initializeSelect2("#prod", "codigo");

  $("#prod").on("select2:select", function (e) {
    const selectedOption = $(e.currentTarget).find(":selected");
    const codigo = selectedOption.data("codigo");
    buscarCodigoVenta(codigo); // Llama a tu función y paso el codigo
  });
  $("#prod_comp").on("select2:select", function (e) {
    const selectedOption = $(e.currentTarget).find(":selected");
    const codigo = selectedOption.data("codigo");
    buscarCodigo(codigo); // Llama a tu función y paso el codigo
  });

  tblUsuarios = $("#tblUsuarios").DataTable({
    language: {
      url: base_url + "Assets/js/es-ES.json",
    },

    ajax: {
      url: base_url + "Usuarios/listar",
      dataSrc: "",
    },

    //atributos de la db que se muestran en la tabla
    columns: [
      {
        data: "id",
      },
      {
        data: "nombre_completo",
      },
      {
        data: "email",
      },
      {
        data: "fyh_reg",
      },
      {
        data: "estado",
      },
      {
        data: "acciones",
      },
    ],
    order: [[3, "desc"]], // Ordena por la columna de fecha en orden descendente
  });

  //tabla de CLIENTES
  tblClientes = $("#tblClientes").DataTable({
    language: {
      url: base_url + "Assets/js/es-ES.json",
    },

    ajax: {
      url: base_url + "Clientes/listar",
      dataSrc: "",
    },
    //atributos de la db que se muestran en la tabla
    columns: [
      {
        data: "nombre_completo",
      },
      {
        data: "cedula",
      },
      {
        data: "email",
      },
      {
        data: "telefono",
      },
      {
        data: "estado",
      },
      {
        data: "acciones",
      },
      { data: "fyh_reg", visible: false }, // Columna de fecha oculta
    ],
    order: [[6, "desc"]], // Ordena por la columna de fecha (índice 6) en orden descendente
  });

  //tabla de Productos (con codigo de botones datatable (esta comentado))
  tblProductos = $("#tblProductos").DataTable({
    language: {
      url: base_url + "Assets/js/es-ES.json",
    },

    ajax: {
      url: base_url + "Productos/listar",
      dataSrc: "",
    },
    //atributos de la db que se muestran en la tabla
    columns: [
      {
        data: "codigo",
      },
      {
        data: "nombre",
      },
      {
        data: "descripcion",
      },
      {
        data: "precio_venta",
        render: function (data, type, row) {
          if (type === "display") {
            const formattedPrice = numberFormat(data, 2, ",", ".");
            return `USD ${formattedPrice}`;
          }
          return data; // Mantener el valor original para otros contextos
        },
      },
      {
        data: "cantidad",
      },
      {
        data: "categoria",
      },
      {
        data: "estado",
      },
      {
        data: "acciones",
      },
      { data: "fech_reg", visible: false }, // Columna de fecha oculta
    ],
    order: [[8, "desc"]],
    /*
    dom: '<"top"lfB>rtip', // Aquí se configura el diseño de los elementos
    buttons: [
      {
        extend: "excel",
        footer: true,
        title: "Archivo",
        filename: "Export_File",
        text: '<div class="excel-button"><span ><i class="fas fa-file-excel"></i> EXCEL</span></div>',
      },
      {
        extend: "pdf",
        footer: true,
        title: "Archivo PDF",
        filename: "Export_File_pdf",
        text: '<div class="pdf-button"><span ><i class="far fa-file-pdf"></i> PDF</span></div>',
      },
    ],
    */
  });

  //tabla de Categoria
  tblCategorias = $("#tblCategorias").DataTable({
    language: {
      url: base_url + "Assets/js/es-ES.json",
    },

    ajax: {
      url: base_url + "Categorias/listar",
      dataSrc: "",
    },
    //atributos de la db que se muestran en la tabla
    columns: [
      {
        data: "id",
      },
      {
        data: "codigo",
      },
      {
        data: "nombre",
      },
      {
        data: "iva",
      },
      {
        data: "estado",
      },
      {
        data: "acciones",
      },
      { data: "fech_reg", visible: false }, // Columna de fecha oculta
    ],
    order: [[6, "desc"]],
  });

  //tabla de historial compras
  t_historial_c = $("#t_historial_c").DataTable({
    language: {
      url: base_url + "Assets/js/es-ES.json",
    },

    ajax: {
      url: base_url + "Compras/listar_historial",
      dataSrc: "",
    },
    //atributos de la db que se muestran en la tabla
    columns: [
      {
        data: "id",
      },
      {
        data: "total",
      },
      {
        data: "fyh_reg",
      },
      {
        data: "estado",
      },
      {
        data: "acciones",
      },
    ],
    order: [[2, "desc"]],
  });

  //tabla de historial ventas
  t_historial_v = $("#t_historial_v").DataTable({
    language: {
      url: base_url + "Assets/js/es-ES.json",
    },

    ajax: {
      url: base_url + "Compras/listar_historial_ventas",
      dataSrc: "",
    },
    //atributos de la db que se muestran en la tabla
    columns: [
      {
        data: "id",
      },
      {
        data: "nombre_completo",
      },
      {
        data: "total",
        render: function (data, type, row) {
          if (type === "display") {
            const formattedPrice = numberFormat(data, 2, ",", ".");
            return `Bs ${formattedPrice}`;
          }
          return data; // Mantener el valor original para otros contextos
        },
      },
      { data: "fyh_reg" },
      {
        data: "estado",
      },
      {
        data: "acciones",
      },
    ],
    order: [[3, "desc"]],
  });

  //tabla de Tasas
  tblTasas = $("#tblTasas").DataTable({
    language: {
      url: base_url + "Assets/js/es-ES.json",
    },

    ajax: {
      url: base_url + "Tasas/listar",
      dataSrc: "",
    },
    //atributos de la db que se muestran en la tabla
    columns: [
      {
        data: "id",
      },
      {
        data: "dolar",
        render: function (data, type, row) {
          if (type === "display") {
            const formattedPrice = numberFormat(data, 2, ",", ".");
            return `Bs ${formattedPrice}`;
          }
          return data; // Mantener el valor original para otros contextos
        },
      },
      {
        data: "euro",
        render: function (data, type, row) {
          if (type === "display") {
            const formattedPrice = numberFormat(data, 2, ",", ".");
            return `Bs ${formattedPrice}`;
          }
          return data; // Mantener el valor original para otros contextos
        },
      },
      {
        data: "fech_reg",
      },
    ],
    order: [[3, "desc"]],
  });
});

//**********************FUNCIONES DEL USUARIO*********************************** */

//****************************************************** */

//****************FUNCIONES PARA CARGAR EL MODAL //pueden mejorar

const modalusu = document.querySelector("#modalusu");

function modalOpenUsu(e) {
  e.preventDefault();

  document.getElementById("title").innerHTML = "Nuevo Usuario";
  document.getElementById("registerUsu").innerHTML = "Registrar";
  //document.getElementById("claves_contra").style.display = "block";//funcion para mostrar este campo del formulario(modal)
  //document.getElementById("claves_confir").style.display = "block";//metodo 1
  document.getElementById("claves_contra").classList.remove("d-none"); //funcion para borrar este campo del formulario(modal)
  document.getElementById("claves_confir").classList.remove("d-none"); //metodo2 (css)

  document.getElementById("insegura").classList.remove("is-invalid");
  document.getElementById("clave_insegura").innerHTML = "";

  // Restablece los valores de los campos
  document.getElementById("frmUsuario").reset(); // Esto restablecerá todos los campos del formulario
  modalusu.classList.add("modal--show");

  //establecemos en cero el id para la validacion
  document.getElementById("id").value = "";
}

function modalCloseUsu(e) {
  e.preventDefault();
  modalusu.classList.remove("modal--show");
}

//funcion para el registro del usuario y cerrar el modal al final
function registerUser(e) {
  e.preventDefault();
  const nombres = document.getElementById("nombres");
  const apellidos = document.getElementById("apellidos");
  const cedula = document.getElementById("cedula");
  const email = document.getElementById("email");
  const telefono = document.getElementById("telefono");
  const secundario = document.getElementById("secundario");
  const direccion = document.getElementById("direccion");
  const usuario = document.getElementById("usuario");

  const validcedula = /^(?=.*[A-Za-z0-9]).{7,9}$/;
  const validsecundario = /^(?=.*[A-Za-z0-9]).{11,11}$/;
  const validtelefono = /^(?=.*[A-Za-z0-9]).{11,11}$/;
  const validEmail = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;

  document.getElementById("insegura").classList.remove("is-invalid");
  document.getElementById("clave_insegura").innerHTML = "";

  //validamos que los campos no esten vacios
  if (
    nombres.value == "" ||
    apellidos.value == "" ||
    cedula.value == "" ||
    email.value == "" ||
    telefono.value == "" ||
    direccion.value == "" ||
    usuario.value == ""
  ) {
    alertas("Todos los campos son obligatorios", "warning");
  } else if (/\d/.test(nombres.value)) {
    alertas("Error en el campo nombre", "error");
  } else if (/\d/.test(apellidos.value)) {
    alertas("Error en el campo apellido", "error");
  } else if (isNaN(cedula.value) || !validcedula.test(cedula.value)) {
    alertas("Error en la campo cédula", "error");
  } else if (isNaN(telefono.value) || !validtelefono.test(telefono.value)) {
    alertas("Teléfono no válido", "warning");
  } else if (!validEmail.test(email.value)) {
    alertas("Correo electrónico no válido", "warning");
  } else {
    //usamos ajax para validar el inicio de secion
    const url = base_url + "Usuarios/registrar"; //enviasmos al controlador usuario metodo registrar
    const frm = document.getElementById("frmUsuario");
    const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
    http.open("POST", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
    http.send(new FormData(frm)); //le enviamos el formulario (supongo a la funcion formdata)
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        //console.log(this.responseText);

        //extraemos el valor de msg de del metodo registrar de usuarios.php
        const res = JSON.parse(this.responseText);
        //mosttramos los mensajes conrrespondientes al registro
        if (res == "si") {
          alertas("Usuario registrado con exito", "success");

          //***************cerramos el modal
          modalusu.classList.remove("modal--show");

          //cargamos si recargar el usuario
          tblUsuarios.ajax.reload();
        } else if (res == "modificado") {
          alertas("Usuario modificado con exito", "success");

          //***************cerramos el modal
          modalusu.classList.remove("modal--show");

          //cargamos si recargar el usuario
          tblUsuarios.ajax.reload();
        } else if (res == "insegura") {
          alertas("Contraseña insegura", "warning", "Minimo 8 caracteres");
          /*document.getElementById("clave_insegura").innerHTML = "Debe tene minimo 8 caracteres";
          document.getElementById("insegura").classList.add("is-invalid");*/
        } else {
          alertas(res.msg, res.icono);
        }
      }
    };
  }
}

//funcion para editar al usuario
function btnEditarUser(id) {
  document.getElementById("title").innerHTML = "Actualizar Usuario";
  document.getElementById("registerUsu").innerHTML = "Actualizar";

  //usamos ajax para validar el inicio de secion
  const url = base_url + "Usuarios/editar/" + id; //enviasmos al controlador usuario metodo editar
  const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
  http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
  http.send(); //le enviamos el formulario (supongo a la funcion formdata)
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      //console.log(this.responseText);

      const res = JSON.parse(this.responseText);

      document.getElementById("id").value = res.id_usuario;
      document.getElementById("nombres").value = res.nombres;
      document.getElementById("apellidos").value = res.apellidos;
      document.getElementById("cedula").value = res.cedula;
      document.getElementById("email").value = res.email;
      document.getElementById("telefono").value = res.telefono;
      document.getElementById("direccion").value = res.direccion;
      document.getElementById("usuario").value = res.usuario;
      //document.getElementById("claves_contra").style.display = "none";//funcion para borrar este campo del formulario(modal)
      //document.getElementById("claves_confir").style.display = "none";//metodo 1 (java-script)
      document.getElementById("claves_contra").classList.add("d-none"); //funcion para borrar este campo del formulario(modal)
      document.getElementById("claves_confir").classList.add("d-none"); //metodo2 (css)

      // ******abrimos el modal
      modalusu.classList.add("modal--show");
    }
  };
}

//funcion para eliminar
function btnEliminarUser(id) {
  Swal.fire({
    title: "Está seguro de Inhabilitar?",
    text: "El usuario no se Inhabilitara de forma permanente!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si!",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      //usamos ajax para validar el inicio de secion
      const url = base_url + "Usuarios/eliminar/" + id; //enviasmos al controlador usuario metodo editar
      const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
      http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
      http.send(); //le enviamos el formulario (supongo a la funcion formdata)
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);

          alertas(res.msg, res.icono);
          //cargamos si recargar el usuario
          tblUsuarios.ajax.reload();
        }
      };
    }
  });
}

function btnReingrsarUser(id) {
  Swal.fire({
    title: "Está seguro de reingresar?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si!",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      //usamos ajax para validar el inicio de secion
      const url = base_url + "Usuarios/reingresar/" + id; //enviasmos al controlador usuario metodo editar
      const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
      http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
      http.send(); //le enviamos el formulario (supongo a la funcion formdata)
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          alertas(res.msg, res.icono);
          //cargamos si recargar el usuario
          tblUsuarios.ajax.reload();
        }
      };
    }
  });
}

/***********CAMBIAR CLAVE DE USUARIO */
const modalClave = document.querySelector("#cambiarPass");

function modalOpenClave(e) {
  e.preventDefault();

  // Restablece los valores de los campos
  document.getElementById("frmCambiarPass").reset(); // Esto restablecerá todos los campos del formulario
  modalClave.classList.add("modal--show");

  //establecemos en cero el id para la validacion
  //document.getElementById("id").value = "";
}

function modalCloseClave(e) {
  e.preventDefault();
  modalClave.classList.remove("modal--show");
}

function cambiarPass(e) {
  e.preventDefault();

  const actual = document.getElementById("clave_actual").value;
  const nueva = document.getElementById("nueva_clave").value;
  const confirmar = document.getElementById("confirmar_clave").value;

  if (actual == "" || nueva == "" || confirmar == "") {
    alertas("Todos los campos son obligatorios", "warning");
  } else {
    //usamos ajax para validar el inicio de secion
    const url = base_url + "Usuarios/cambiarPass"; //enviasmos al controlador usuario metodo registrar
    const frm = document.getElementById("frmCambiarPass");
    const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
    http.open("POST", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
    http.send(new FormData(frm)); //le enviamos el formulario (supongo a la funcion formdata)
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        //console.log(this.responseText);
        //extraemos el valor de msg de del metodo registrar de usuarios.php
        const res = JSON.parse(this.responseText);

        if (res == "ok") {
          alertas("Contraseña modificada con exito", "success");
          //***************cerramos el modal
          modalClave.classList.remove("modal--show");
        } else if (res == "insegura") {
          alertas(
            "La nueva contraseña es insegura",
            "warning",
            "Minimo 8 caracteres"
          );
        } else {
          alertas(res.msg, res.icono);
        }
      }
    };
  }
}

//*********************FIN DE LAS FUNCIONES DEL USUARIO************************ */

//***********************************************************************************/

//**********************FUNCIONES DEL CLIENTE*********************************** */

//****************FUNCIONES PARA CARGAR EL MODAL //pueden mejorar

const modalCli = document.querySelector("#modalCli");
const modalCliDetalles = document.querySelector("#modalCliDetalles");

function modalOpenCli(e) {
  e.preventDefault();

  document.getElementById("title").innerHTML = "Nuevo Cliente";
  document.getElementById("register_Cli").innerHTML = "Registrar";

  // Restablece los valores de los campos
  document.getElementById("frmCliente").reset(); // Esto restablecerá todos los campos del formulario
  modalCli.classList.add("modal--show");

  //establecemos en cero el id para la validacion
  document.getElementById("id").value = "";
}

function modalCloseCli(e) {
  e.preventDefault();
  modalCli.classList.remove("modal--show");
  modalCliDetalles.classList.remove("modal--show");
}

//funcion para el registro del usuario y cerrar el modal al final
function registerCli(e) {
  e.preventDefault();
  const nombres = document.getElementById("nombres");
  const apellidos = document.getElementById("apellidos");
  const cedula = document.getElementById("cedula");
  const email = document.getElementById("email");
  const telefono = document.getElementById("telefono");
  const direccion = document.getElementById("direccion");

  const id = document.getElementById("id");

  const validcedula = /^(?=.*[A-Za-z0-9]).{6,9}$/;
  const validsecundario = /^(?=.*[A-Za-z0-9]).{11,11}$/;
  const validtelefono = /^(?=.*[A-Za-z0-9]).{11,11}$/;
  const validEmail = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;

  //validamos que los campos no esten vacios
  if (
    nombres.value == "" ||
    apellidos.value == "" ||
    cedula.value == "" ||
    email.value == "" ||
    telefono.value == "" ||
    direccion.value == ""
  ) {
    alertas("Todos los campos son obligatorios", "warning");
  } else if (/\d/.test(nombres.value)) {
    alertas("Error en el campo nombre", "error");
  } else if (/\d/.test(apellidos.value)) {
    alertas("Error en el campo apellido", "error");
  } else if (isNaN(cedula.value) || !validcedula.test(cedula.value)) {
    alertas("Error en la campo cédula", "error");
  } else if (isNaN(telefono.value) || !validtelefono.test(telefono.value)) {
    alertas("Teléfono no válido", "warning");
  } else if (!validEmail.test(email.value)) {
    alertas("Correo electrónico no válido", "warning");
  } else {
    //usamos ajax para validar el inicio de secion
    const url = base_url + "Clientes/registrar"; //enviasmos al controlador usuario metodo registrar
    const frm = document.getElementById("frmCliente");
    const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
    http.open("POST", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
    http.send(new FormData(frm)); //le enviamos el formulario (supongo a la funcion formdata)
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        //console.log(this.responseText);

        //extraemos el valor de msg de del metodo registrar de usuarios.php
        const res = JSON.parse(this.responseText);

        //mosttramos los mensajes conrrespondientes al registro
        if (res == "si") {
          alertas("Cliente registrado con exito", "success");

          //***************cerramos el modal
          modalCli.classList.remove("modal--show");

          //cargamos si recargar el usuario
          tblClientes.ajax.reload();
        } else if (res == "modificado") {
          alertas("Cliente modificado con exito", "success");

          //***************cerramos el modal
          modalCli.classList.remove("modal--show");

          //cargamos si recargar el usuario
          tblClientes.ajax.reload();
        } else {
          alertas(res.msg, res.icono);
        }
      }
    };
  }
}

//funcion para editar al Cliente
function btnEditarCli(id) {
  document.getElementById("title").innerHTML = "Actualizar Cliente";
  document.getElementById("register_Cli").innerHTML = "Actualizar";

  //usamos ajax para validar el inicio de secion
  const url = base_url + "Clientes/editar/" + id; //enviasmos al controlador usuario metodo editar
  const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
  http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
  http.send(); //le enviamos el formulario (supongo a la funcion formdata)
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      //console.log(this.responseText);
      const res = JSON.parse(this.responseText);

      document.getElementById("id").value = res.id;
      document.getElementById("nombres").value = res.nombres;
      document.getElementById("apellidos").value = res.apellidos;
      document.getElementById("cedula").value = res.cedula;
      document.getElementById("email").value = res.email;
      document.getElementById("telefono").value = res.telefono;
      document.getElementById("direccion").value = res.direccion;

      // ******abrimos el modal
      modalCli.classList.add("modal--show");
    }
  };
}

//FUCIONMOTRAR DETALLES DEL CLIENTE
function btnDetallesCli(id) {
  document.getElementById("titled").innerHTML = "Detalles Del Cliente";

  //usamos ajax para validar el inicio de secion
  const url = base_url + "Clientes/editar/" + id; //enviasmos al controlador usuario metodo editar
  const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
  http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
  http.send(); //le enviamos el formulario (supongo a la funcion formdata)
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      //console.log(this.responseText);
      const res = JSON.parse(this.responseText);

      document.getElementById("id2").value = res.id;
      document.getElementById("nombress").value = res.nombres;
      document.getElementById("apellidoss").value = res.apellidos;
      document.getElementById("cedulas").value = res.cedula;
      document.getElementById("emails").value = res.email;
      document.getElementById("telefonos").value = res.telefono;
      document.getElementById("direccions").value = res.direccion;
      document.getElementById("fech_reg").value = res.fyh_reg;
      document.getElementById("fech_act").value = res.fyh_act;

      // ******abrimos el modal
      modalCliDetalles.classList.add("modal--show");
    }
  };
}

//funcion para eliminar
function btnEliminarCli(id) {
  Swal.fire({
    title: "Está seguro de Inhabilitar?",
    text: "El Cliente no se Inhabilitara de forma permanente!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si!",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      //usamos ajax para validar el inicio de secion
      const url = base_url + "Clientes/eliminar/" + id; //enviasmos al controlador usuario metodo editar
      const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
      http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
      http.send(); //le enviamos el formulario (supongo a la funcion formdata)
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          alertas(res.msg, res.icono);
          //cargamos si recargar el usuario
          tblClientes.ajax.reload();
        }
      };
    }
  });
}

function btnReingrsarCli(id) {
  Swal.fire({
    title: "Está seguro de reingresar?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si!",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      //usamos ajax para validar el inicio de secion
      const url = base_url + "Clientes/reingresar/" + id; //enviasmos al controlador usuario metodo editar
      const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
      http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
      http.send(); //le enviamos el formulario (supongo a la funcion formdata)
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          alertas(res.msg, res.icono);
          //cargamos si recargar el usuario
          tblClientes.ajax.reload();
        }
      };
    }
  });
}

//*********************FIN DE LAS FUNCIONES DEL CLIENTE************************ */

//***********************************************************************************/

//**********************FUNCIONES DEL Producto*********************************** */

//****************FUNCIONES PARA CARGAR EL MODAL //pueden mejorar

const modalPro = document.querySelector("#modalPro");

function modalOpenPro(e) {
  e.preventDefault();

  document.getElementById("title").innerHTML = "Nuevo Producto";
  document.getElementById("register_Pro").innerHTML = "Registrar";

  // Restablece los valores de los campos
  document.getElementById("frmProducto").reset(); // Esto restablecerá todos los campos del formulario
  modalPro.classList.add("modal--show");

  //establecemos en cero el id para la validacion
  document.getElementById("id").value = "";
}

function modalClosePro(e) {
  e.preventDefault();
  modalPro.classList.remove("modal--show");
}

//funcion para el registro del usuario y cerrar el modal al final
function registerPro(e) {
  e.preventDefault();
  const nombre = document.getElementById("nombre");
  const descripcion = document.getElementById("descripcion");
  const codigo = document.getElementById("codigo");
  const precio_compra = document.getElementById("precio_compra").value;
  const precio_venta = document.getElementById("precio_venta").value;
  const categoria = document.getElementById("categoria");

  const precio_compra_puntos = precio_compra.replace(/,/g, ".");
  const precio_venta_puntos = precio_venta.replace(/,/g, ".");
  const validprecio = /^[0-9.]+$/;

  const id = document.getElementById("id");

  //validamos que los campos no esten vacios
  if (
    nombre.value == "" ||
    descripcion.value == "" ||
    codigo.value == "" ||
    precio_compra == "" ||
    precio_venta == "" ||
    categoria.value == ""
  ) {
    alertas("Todos los campos son obligatorios", "warning");
  } else if (isNaN(codigo.value)) {
    alertas("Error en el campo código", "error");
  } else if (!validprecio.test(precio_compra_puntos)) {
    alertas("Error en el precio de Compra", "error");
  } else if (!validprecio.test(precio_venta_puntos)) {
    alertas("Error en el precio de venta", "error");
  } else {
    //usamos ajax para validar el inicio de secion
    const url = base_url + "Productos/registrar"; //enviasmos al controlador usuario metodo registrar
    const frm = document.getElementById("frmProducto");
    const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
    http.open("POST", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
    http.send(new FormData(frm)); //le enviamos el formulario (supongo a la funcion formdata)
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        //console.log(this.responseText);

        //extraemos el valor de msg de del metodo registrar de usuarios.php
        const res = JSON.parse(this.responseText);
        //mosttramos los mensajes conrrespondientes al registro
        if (res == "si") {
          alertas("Producto registrado con exito", "success");
          //***************cerramos el modal
          modalPro.classList.remove("modal--show");

          //cargamos si recargar el usuario
          tblProductos.ajax.reload();
        } else if (res == "modificado") {
          alertas("Producto Modificado con Exito", "success");

          //***************cerramos el modal
          modalPro.classList.remove("modal--show");

          //cargamos si recargar el usuario
          tblProductos.ajax.reload();
        } else {
          alertas(res.msg, res.icono);
        }
      }
    };
  }
}

//funcion para editar al Cliente
function btnEditarPro(id) {
  document.getElementById("title").innerHTML = "Actualizar Producto";
  document.getElementById("register_Pro").innerHTML = "Actualizar";

  //usamos ajax para validar el inicio de secion
  const url = base_url + "Productos/editar/" + id; //enviasmos al controlador usuario metodo editar
  const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
  http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
  http.send(); //le enviamos el formulario (supongo a la funcion formdata)
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);

      document.getElementById("id").value = res.id;
      document.getElementById("nombre").value = res.nombre;
      document.getElementById("descripcion").value = res.descripcion;
      document.getElementById("codigo").value = res.codigo;
      document.getElementById("precio_compra").value = res.precio_compra;
      document.getElementById("precio_venta").value = res.precio_venta;
      document.getElementById("categoria").value = res.id_categoria;

      // ******abrimos el modal
      modalPro.classList.add("modal--show");
    }
  };
}

//funcion para eliminar
function btnEliminarPro(id) {
  Swal.fire({
    title: "Está seguro de Inhabilitar?",
    text: "El Producto no se Inhabilitara de forma permanente!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si!",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      //usamos ajax para validar el inicio de secion
      const url = base_url + "Productos/eliminar/" + id; //enviasmos al controlador usuario metodo editar
      const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
      http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
      http.send(); //le enviamos el formulario (supongo a la funcion formdata)
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          alertas(res.msg, res.icono);
          //cargamos si recargar el usuario
          tblProductos.ajax.reload();
        }
      };
    }
  });
}

function btnReingrsarPro(id) {
  Swal.fire({
    title: "Está seguro de reingresar?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si!",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      //usamos ajax para validar el inicio de secion
      const url = base_url + "Productos/reingresar/" + id; //enviasmos al controlador usuario metodo editar
      const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
      http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
      http.send(); //le enviamos el formulario (supongo a la funcion formdata)
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);

          alertas(res.msg, res.icono);
          //cargamos si recargar el usuario
          tblProductos.ajax.reload();
        }
      };
    }
  });
}

//*********************FIN DE LAS FUNCIONES DEL Producto************************ */

//***********************************************************************************/

//**********************FUNCIONES DE LA CATEGORIA*********************************** */

//****************FUNCIONES PARA CARGAR EL MODAL //pueden mejorar

const modalCat = document.querySelector("#modalCat");

function modalOpenCat(e) {
  e.preventDefault();

  document.getElementById("title").innerHTML = "Nueva Categoria";
  document.getElementById("register_Cat").innerHTML = "Registrar";

  // Restablece los valores de los campos
  document.getElementById("frmCategotoria").reset(); // Esto restablecerá todos los campos del formulario
  modalCat.classList.add("modal--show");

  //establecemos en cero el id para la validacion
  document.getElementById("id").value = "";
}

function modalCloseCat(e) {
  e.preventDefault();
  modalCat.classList.remove("modal--show");
}

//funcion para el registro del usuario y cerrar el modal al final
function registerCat(e) {
  e.preventDefault();
  const nombre = document.getElementById("nombre");
  const codigo = document.getElementById("codigo");
  const id = document.getElementById("id");
  const iva = document.getElementById("iva");

  //validamos que los campos no esten vacios
  if (nombre.value == "" || iva.value == "") {
    alertas("Todos los campos son obligatorios", "warning");
  } else if (/\d/.test(nombre.value)) {
    alertas("Error en el campo nombre", "error");
  } else {
    //usamos ajax para validar el inicio de secion
    const url = base_url + "Categorias/registrar"; //enviasmos al controlador usuario metodo registrar
    const frm = document.getElementById("frmCategotoria");
    const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
    http.open("POST", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
    http.send(new FormData(frm)); //le enviamos el formulario (supongo a la funcion formdata)
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        console.log(this.responseText);

        //extraemos el valor de msg de del metodo registrar de usuarios.php
        const res = JSON.parse(this.responseText);

        //mosttramos los mensajes conrrespondientes al registro
        if (res == "si") {
          alertas("Categoria registrada con exito", "success");

          //***************cerramos el modal
          modalCat.classList.remove("modal--show");

          //cargamos si recargar el usuario
          tblCategorias.ajax.reload();
        } else if (res == "modificado") {
          alertas("Categoria modificada con exito", "success");

          //***************cerramos el modal
          modalCat.classList.remove("modal--show");

          //cargamos si recargar el usuario
          tblCategorias.ajax.reload();
        } else {
          alertas(res.msg, res.icono);
        }
      }
    };
  }
}

//funcion para editar al Cliente
function btnEditarCat(id) {
  document.getElementById("title").innerHTML = "Actualizar Categoria";
  document.getElementById("register_Cat").innerHTML = "Actualizar";

  //usamos ajax para validar el inicio de secion
  const url = base_url + "Categorias/editar/" + id; //enviasmos al controlador usuario metodo editar
  const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
  http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
  http.send(); //le enviamos el formulario (supongo a la funcion formdata)
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);

      document.getElementById("id").value = res.id;
      document.getElementById("nombre").value = res.nombre;
      document.getElementById("codigo").value = res.codigo;
      document.getElementById("iva").value = res.iva;

      // ******abrimos el modal
      modalCat.classList.add("modal--show");
    }
  };
}

//funcion para eliminar
function btnEliminarCat(id) {
  Swal.fire({
    title: "Está seguro de Inhabilitar?",
    text: "La Categoria no se Inhabilitara de forma permanente!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si!",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      //usamos ajax para validar el inicio de secion
      const url = base_url + "Categorias/eliminar/" + id; //enviasmos al controlador usuario metodo editar
      const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
      http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
      http.send(); //le enviamos el formulario (supongo a la funcion formdata)
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          alertas(res.msg, res.icono);
          //cargamos si recargar el usuario
          tblCategorias.ajax.reload();
        }
      };
    }
  });
}

function btnReingrsarCat(id) {
  Swal.fire({
    title: "Está seguro de reingresar?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si!",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      //usamos ajax para validar el inicio de secion
      const url = base_url + "Categorias/reingresar/" + id; //enviasmos al controlador usuario metodo editar
      const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
      http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
      http.send(); //le enviamos el formulario (supongo a la funcion formdata)
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          alertas(res.msg, res.icono);
          //cargamos si recargar el usuario
          tblCategorias.ajax.reload();
        }
      };
    }
  });
}
//*********************FIN DE LAS FUNCIONES DEL CATEGORIA************************ */

//***********************************************************************************/

//**********************FUNCIONES DE LA STOCK (COMPRAS)*********************************** */

//****************FUNCIONES PARA CARGAR EL MODAL //pueden mejorar

// Variable para rastrear si se presionó "Enter" en el campo de código
let enterPresionado = false;
var precio_comprax = 0;
//funcion para buscar el producto en el modulo stock
function buscarCodigo(cod) {
  //e.preventDefault();
  //const cod = document.getElementById("codigo").value;

  // Se presionó "Enter"
  enterPresionado = true;

  //usamos ajax para validar el inicio de secion
  const url = base_url + "Compras/buscarCodigo/" + cod; //enviasmos al controlador usuario metodo editar
  const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
  http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
  http.send(); //le enviamos el formulario (supongo a la funcion formdata)
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      //console.log(this.responseText);

      const res = JSON.parse(this.responseText);

      if (res) {
        //obtenmos todos los valores del producto
        precio_comprax = res.precio_compra;
        document.getElementById("nombre").value = res.nombre;
        document.getElementById("descrip").value = res.descripcion;

        document.getElementById(
          "precio_compra"
        ).value = `Bs ${res.precio_compra}`;
        document.getElementById("id").value = res.id;
        document.getElementById("cantidad").focus();
      } else {
        alertas("El Producto no existe", "warning");

        //resetea el valor y redireciona el cursor
        document.getElementById("codigo").value = "";
        document.getElementById("codigo").focus();
      }
    }
  };
}

//funcion para evitar que escriban puntos en la cantidad stock
function preventDot(e) {
  if (e.charCode === 46) {
    e.preventDefault(); // No se permite escribir el punto.
  }
}

//funcion para calcular el sub total cantidad y demas
function calcularPrecio(e) {
  e.preventDefault();
  //const cod = document.getElementById("codigo").value;
  var cant = document.getElementById("cantidad").value;
  var nombre = document.getElementById("nombre").value;
  //const numDecimal = /^-?\d*\.?\d*$/;

  if (!enterPresionado || !nombre) {
    // Verificar si ya se mostró la alerta previamente
    const alertaMostrada = document.getElementById("alerta-no-producto");
    //enterPresionado = false;
    //document.getElementById("codigo").value = "";
    if (!alertaMostrada) {
      alertas("Seleccione un Producto", "warning");

      // Crear un elemento oculto para marcar que se mostró la alerta
      const alertaElement = document.createElement("div");
      alertaElement.id = "alerta-no-producto";
      alertaElement.style.display = "none";
      document.body.appendChild(alertaElement);

      // Establecer un temporizador para restablecer el estado del elemento oculto
      setTimeout(() => {
        document.getElementById("alerta-no-producto").remove();
      }, 1200); // Cambia el tiempo (en milisegundos) según tus necesidades
    }

    setTimeout(() => {
      //validadcion para introducir la cantidad
      document.getElementById("cantidad").value = "";
      document.getElementById("prod_comp").focus();
    }, 600);

    //validamos que la cantidad sea mayor a cero
  } else if (cant < 1 && cant != "") {
    alertas("Error con la cantidad", "warning");

    //validadcion para introducir la cantidad
    document.getElementById("cantidad").value = "";
  } else if (cant == "") {
    //valida para que no entre cuando este vacio
    //y para que no muestre una alerta si esta vacio
    //pude mejorar, pero funciona al pelo
    document.getElementById("sub_total").value = "";
  } else {
    //hacemos el proceso de calculo

    var sub_total = numberFormat(precio_comprax * cant, 2, ",", ".");
    document.getElementById("sub_total").value = `Bs ${sub_total}`;
    //formato en dolares
    //document.getElementById("sub_total").value = (precio * cant).toLocaleString('es', { style: 'currency', currency: 'USD' });
    if (e.which == 13) {
      //registramos los productos en la tabla detalle(carrito de la compra)
      const url = base_url + "Compras/ingresar"; //enviasmos al controlador usuario metodo editar
      const frm = document.getElementById("frmCompra");
      const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
      http.open("POST", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
      http.send(new FormData(frm)); //le enviamos el formulario (supongo a la funcion formdata)
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          //console.log(this.responseText);
          const res = JSON.parse(this.responseText);

          //registramos el producto en el carrito
          //si estos elementos se colocan en el mismos orden que los de la venta da error

          if (res == "ok") {
            //se puede poner una alrta aqui
            alertas("Producto ingresado", "success", "", 2000);
            frm.reset();
            cargarDetalle();
            document.getElementById("prod_comp").focus();
            $("#prod_comp").val(null).trigger("change");
          } else if (res == "modificado") {
            //actualizamos el producto del carrito

            //se puede poner una alrta aqui
            frm.reset();
            cargarDetalle();
            document.getElementById("prod_comp").focus();
            $("#prod_comp").val(null).trigger("change");
          }
        }
      };
    }
  }
}

//validacion para los productos en existencia para la compra
var prod_exist_detalle_compra = 0;

//hacemos el llamado fuera para que se muestre de forma instantanea
//y validamos que exista
if (document.getElementById("tblDetalle")) {
  cargarDetalle();
}

//listamos todos los productos de la tabla detalle
function cargarDetalle() {
  const url = base_url + "Compras/listar/detalle"; //enviasmos al controlador usuario metodo editar
  const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
  http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
  http.send(); //le enviamos el formulario (supongo a la funcion formdata)
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      //console.log(this.responseText);
      const res = JSON.parse(this.responseText);
      let html = "";
      let i = 1;
      //creamos la tabla de el stock(el carrito de la compra)
      res.detalle.forEach((row) => {
        html += `<tr>
        <td>${i++}</td>
        <td>${row["nombre"]}</td>
        <td>${row["descripcion"]}</td>
        <td>${row["cantidad"]}</td>
        <td>${"Bs " + numberFormat(row["precio"], 2, ",", ".")}</td>
        <td>${"Bs " + numberFormat(row["sub_total"], 2, ",", ".")}</td>
        <td >
        <div class="acciones_crud">
          <button title="Inhabilitar" class="boton_acciones_delete" type="button" 
          onclick="deleteDetalle(${row["id"]},1);">
          <i class="fas fa-trash-alt"></i></button>
        </div>
        </td>
        </tr>`;
      });
      if (html != "") {
        document.getElementById("tblDetalle").innerHTML = html;
        document.getElementById("boton_compra").classList.remove("d-none");
        //mostramos el total a pagar formateado
        let total = numberFormat(res.total_pagar, 2, ",", ".");
        document.getElementById("total").value = `Bs ${total}`;
        //variable para la validacion de la cantidad de productos
        prod_exist_detalle_compra = 1;
      } else {
        document.getElementById("boton_compra").classList.add("d-none");
        prod_exist_detalle_compra = 0;
        document.getElementById("tblDetalle").innerHTML = "";
        document.getElementById("total").value = "";
      }
    }
  };
}

//eliminamos el producto del carrito de la compra
function deleteDetalle(id, accion) {
  Swal.fire({
    title: "Está seguro de Eliminar?",
    text: "El Registro se Eliminara de forma permanente!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si!",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      let url;
      //las compras es accion = 1
      if (accion == 1) {
        url = base_url + "Compras/delete/" + id; //enviasmos al controlador usuario metodo editar
      } else {
        url = base_url + "Compras/deleteVenta/" + id; //enviasmos al controlador usuario metodo editar
      }

      const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
      http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
      http.send(); //le enviamos el formulario (supongo a la funcion formdata)
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          //console.log(this.responseText);

          const res = JSON.parse(this.responseText);

          if (res == "ok") {
            alertas("Registro Eliminado", "success");
            //
            if (accion == 1) {
              //recargamos la tabla
              cargarDetalle();
            } else {
              cargarDetalleVenta();
            }
          } else {
            alertas(res.msg, res.icono);
          }
        }
      };
    }
  });
}

//generamos la compra o venta final
function procesar(accion) {
  //validacion para que la compra no se ejecute si no hay productos en el carrito
  //super validacion fuaaaaa
  if (
    (prod_exist_detalle_compra > 0 && accion == 1) ||
    (prod_exist_detalle_venta > 0 && accion != 1)
  ) {
    let titulo = "";
    if (accion == 1) {
      titulo = "Está seguro de realizar el ingreso?";
    } else {
      titulo = "Está seguro de realizar la venta?";
    }
    Swal.fire({
      title: titulo,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si!",
      cancelButtonText: "No",
    }).then((result) => {
      if (result.isConfirmed) {
        let url;
        //accion para las compras
        if (accion == 1) {
          //
          url = base_url + "Compras/registrarCompra"; //enviasmos al controlador usuario metodo editar
        } else {
          //
          const id_cliente = document.getElementById("cliente").value;
          url = base_url + "Compras/registrarVenta/" + id_cliente; //enviasmos al controlador usuario metodo editar
        }
        //usamos ajax para validar el inicio de secion
        const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
        http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
        http.send(); //le enviamos el formulario (supongo a la funcion formdata)
        http.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);

            const res = JSON.parse(this.responseText);

            if (res.msg == "ok") {
              alertas("Proceso Exitoso", "success");
              let ruta;
              if (accion == 1) {
                ruta = base_url + "Compras/generarPdf/" + res.id_compra;
              } else {
                ruta = base_url + "Compras/generarPdfVenta/" + res.id_venta;
              }
              window.open(ruta);
              setTimeout(() => {
                window.location.reload();
              }, 300);
            } else {
              alertas(res, "error");
            }
          }
        };
      }
    });
  } else {
    alertas("Introduzca un produto", "error", "", 2000);
  }
}

//anular Compra
function btnAnularC(id) {
  Swal.fire({
    title: "Está seguro de anular el ingreso?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si!",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      //ajax
      const url = base_url + "Compras/anularCompra/" + id; //enviasmos al controlador usuario metodo editar
      //usamos ajax para validar el inicio de secion
      const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
      http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
      http.send(); //le enviamos el formulario (supongo a la funcion formdata)
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          //console.log(this.responseText);

          const res = JSON.parse(this.responseText);
          alertas(res.msg, res.icono);
          t_historial_c.ajax.reload();
        }
      };
    }
  });
}
//*********************FIN DE LAS FUNCIONES DE LA  COMPRA************************ */

//***********************************************************************************/

//**********************FUNCIONES DE LA VENTA *********************************** */

//****************FUNCIONES PARA CARGAR EL MODAL //pueden mejorar
//validacion para los productos en existencia para la venta
var prod_exist_detalle_venta = 0;
var precio_ventax = 0;
function buscarCodigoVenta(cod) {
  enterPresionado = true;

  //usamos ajax para validar el inicio de secion
  const url = base_url + "Compras/buscarCodigo/" + cod; //enviasmos al controlador usuario metodo editar
  const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
  http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
  http.send(); //le enviamos el formulario (supongo a la funcion formdata)
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      //console.log(this.responseText);

      const res = JSON.parse(this.responseText);

      if (res) {
        //obtenmos todos los valores del producto
        precio_ventax = res.precio_venta_bs;
        document.getElementById("nombre").value = res.nombre;
        document.getElementById("descrip").value = res.descripcion;

        var precio,
          precio_bs = 0;

        precio = numberFormat(res.precio_venta, 2, ",", ".");
        document.getElementById("precio_venta").value = `$ ${precio}`;

        precio_bs = numberFormat(res.precio_venta_bs, 2, ",", ".");
        document.getElementById("precio_venta_bs").value = `Bs ${precio_bs}`;

        document.getElementById("id").value = res.id;
        document.getElementById("cantidad").focus();
      } else {
        alertas("El Producto no existe", "warning");

        //resetea el valor y redireciona el cursor
        document.getElementById("codigo").value = "";
        document.getElementById("codigo").focus();
      }
    }
  };
}

//funcion para calcular el sub total cantidad y demas
function calcularPrecioVenta(e) {
  e.preventDefault();
  //const cod = document.getElementById("codigo").value;
  var cant = document.getElementById("cantidad").value;
  var nombre = document.getElementById("nombre").value;
  //const numDecimal = /^-?\d*\.?\d*$/;

  if (!enterPresionado || !nombre) {
    // Verificar si ya se mostró la alerta previamente
    const alertaMostrada = document.getElementById("alerta-no-producto");
    //enterPresionado = false;
    //document.getElementById("codigo").value = "";
    if (!alertaMostrada) {
      alertas("Seleccione un Producto", "warning");

      // Crear un elemento oculto para marcar que se mostró la alerta
      const alertaElement = document.createElement("div");
      alertaElement.id = "alerta-no-producto";
      alertaElement.style.display = "none";
      document.body.appendChild(alertaElement);

      // Establecer un temporizador para restablecer el estado del elemento oculto
      setTimeout(() => {
        document.getElementById("alerta-no-producto").remove();
      }, 1200); // Cambia el tiempo (en milisegundos) según tus necesidades
    }

    setTimeout(() => {
      //validadcion para introducir la cantidad
      document.getElementById("cantidad").value = "";
      document.getElementById("prod").focus();
    }, 600);

    //validamos que la cantidad sea mayor a cero
  } else if (cant < 1 && cant != "") {
    alertas("Error con la cantidad", "warning");

    //validadcion para introducir la cantidad
    document.getElementById("cantidad").value = "";
  } else if (cant == "") {
    //valida para que no entre cuando este vacio
    //y para que no muestre una alerta si esta vacio
    //pude mejorar, pero funciona al pelo
    document.getElementById("sub_total").value = "";
  } else {
    //hacemos el proceso de calculo

    var sub_total = numberFormat(precio_ventax * cant, 2, ",", ".");
    document.getElementById("sub_total").value = `Bs ${sub_total}`;
    //formato en dolares
    //document.getElementById("sub_total").value = (precio * cant).toLocaleString('es', { style: 'currency', currency: 'USD' });
    if (e.which == 13) {
      //registramos los productos en la tabla detalle(carrito de la compra)
      const url = base_url + "Compras/ingresarVenta"; //enviasmos al controlador usuario metodo editar
      const frm = document.getElementById("frmVenta");
      const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
      http.open("POST", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
      http.send(new FormData(frm)); //le enviamos el formulario (supongo a la funcion formdata)
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          //console.log(this.responseText);
          const res = JSON.parse(this.responseText);

          //registramos el producto en el carrito
          //si estos elementos se colocan en el mismos orden que los de la compra da error

          if (res == "ok") {
            //reseteamos el formulario y el select
            document.getElementById("prod").focus();
            frm.reset();
            $("#prod").val(null).trigger("change");

            //se puede poner una alrta aqui
            alertas("Producto ingresado", "success", "", 2000);
            cargarDetalleVenta();
          } else if (res == "modificado") {
            //actualizamos el producto del carrito

            //se puede poner una alrta aqui
            cargarDetalleVenta();

            //reseteamos el formulario y el select
            document.getElementById("prod").focus();
            frm.reset();
            $("#prod").val(null).trigger("change");
          }
        }
      };
    }
  }
}
//hacemos el llamado fuera para que se muestre de forma instantanea
//y validamos que exista
if (document.getElementById("tblDetalleVenta")) {
  cargarDetalleVenta();
}
//listamos todos los productos de la tabla detalle
function cargarDetalleVenta() {
  const url = base_url + "Compras/listar/detalle_temp"; //enviasmos al controlador usuario metodo editar
  const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
  http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
  http.send(); //le enviamos el formulario (supongo a la funcion formdata)
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      //console.log(this.responseText);
      const res = JSON.parse(this.responseText);
      let html = "";
      let i = 1;
      //creamos la tabla de el stock(el carrito de la compra)
      res.detalle.forEach((row) => {
        html += `<tr>
        <td>${i++}</td>
        <td>${row["nombre"]}</td>
        <td>${row["descripcion"]}</td>
        <td>${row["cantidad"]}</td>
        <td>${"USD " + numberFormat(row["precio"], 2, ",", ".")}</td>
        <td>${"USD " + numberFormat(row["sub_total"], 2, ",", ".")}</td>
        <td>${"Bs " + numberFormat(row["sub_total_bs"], 2, ",", ".")}</td>
        <td >
        <div class="acciones_crud">
          <button title="Inhabilitar" class="boton_acciones_delete" type="button"
           onclick="deleteDetalle(${row["id"]}, 2);">
          <i class="fas fa-trash-alt"></i></button>
        </div>
        </td>
        </tr>`;
      });
      if (html != "") {
        document.getElementById("tblDetalleVenta").innerHTML = html;
        document.getElementById("boton_venta").classList.remove("d-none");

        //mostramos el total a pagar formateado
        let total = numberFormat(res.total_pagar, 2, ",", ".");
        document.getElementById("total").value = `USD ${total}`;

        let total_bs = numberFormat(res.total_pagar_bs, 2, ",", ".");
        document.getElementById("total_bs").value = `Bs ${total_bs}`;
        //variable para la validacion de la cantidad de productos
        prod_exist_detalle_venta = 1;
      } else {
        document.getElementById("boton_venta").classList.add("d-none");

        prod_exist_detalle_venta = 0;
        document.getElementById("tblDetalleVenta").innerHTML = "";
        document.getElementById("total").value = "";
        document.getElementById("total_bs").value = "";
      }
    }
  };
}

//anular Venta
function btnAnularV(id) {
  Swal.fire({
    title: "Está seguro de anular la venta?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si!",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      //ajax
      const url = base_url + "Compras/anularVenta/" + id; //enviasmos al controlador usuario metodo editar
      //usamos ajax para validar el inicio de secion
      const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
      http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
      http.send(); //le enviamos el formulario (supongo a la funcion formdata)
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          //console.log(this.responseText);

          const res = JSON.parse(this.responseText);
          alertas(res.msg, res.icono);
          t_historial_v.ajax.reload();
        }
      };
    }
  });
}
//*********************FIN DE LAS FUNCIONES DE LA VENTA************************ */

//***********************************************************************************/

//**********************FUNCIONES DE LA Empresa y FUCIONES personalizadas *********************************** */

//****************FUNCIONES PARA CARGAR EL MODAL //pueden mejorar
//modificar datos de la empresa
function modificarEmpresa() {
  //usamos ajax para validar el inicio de secion
  const frm = document.getElementById("frmEmpresa");
  const url = base_url + "Administracion/modificar"; //enviasmos al controlador usuario metodo editar
  const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
  http.open("POST", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
  http.send(new FormData(frm)); //le enviamos el formulario (supongo a la funcion formdata)
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      //console.log(this.responseText);
      const res = JSON.parse(this.responseText);

      if (res == "ok") {
        alertas("Datos modificados con exito", "success");
      } else {
        alertas(res.msg, res.icono);
      }
    }
  };
}
function modificarTasas() {
  Swal.fire({
    title: "Está seguro de modificar la tasa?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si!",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      const dolar = document.getElementById("dolar").value;
      const euro = document.getElementById("euro").value;

      const precio_dolar_puntos = dolar.replace(/,/g, ".");
      const precio_euro_puntos = euro.replace(/,/g, ".");
      const validprecio = /^[0-9.]+$/;

      if (dolar == "" || euro == "") {
        alertas("Todos los campos son obligatorios", "warning");
      } else if (!validprecio.test(precio_euro_puntos)) {
        alertas("Error en el precio del Euro", "error");
      } else if (!validprecio.test(precio_dolar_puntos)) {
        alertas("Error en el precio del Dólar", "error");
      } else {
        //usamos ajax para validar el inicio de secion
        const frm = document.getElementById("frmTasas");
        const url = base_url + "Tasas/modificar"; //enviasmos al controlador usuario metodo editar
        const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
        http.open("POST", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
        http.send(new FormData(frm)); //le enviamos el formulario (supongo a la funcion formdata)
        http.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            //console.log(this.responseText);
            const res = JSON.parse(this.responseText);

            if (res == "ok") {
              alertas("Datos modificados con exito", "success");
              tblTasas.ajax.reload();
            } else {
              alertas(res.msg, res.icono);
            }
          }
        };
      }
    }
  });
}
// Función para actualizar la tasa del dólar automáticamente
function actualizarTasaDolar() {
  //usamos ajax para validar el inicio de secion
  const url = base_url + "Tasas/actualizarTasaDolar"; //enviasmos al controlador usuario metodo editar
  const http = new XMLHttpRequest(); //instancimos el objeto xmlhttp...
  http.open("GET", url, true); //abrimos una conxion por el metodo post, le enviamos el url y se ejecuta de forma asincrona
  http.send(); //le enviamos el formulario (supongo a la funcion formdata)
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      //console.log(this.responseText);
      const res = JSON.parse(this.responseText);

      if (res == "ok") {
        alertas("Tasas actualizadas con éxito", "success");
        tblTasas.ajax.reload();
      } else {
        alertas(res.msg, res.icono, "", 4000);
      }
    }
  };
}
/*
esta es una version utlizando fetch, no lo entiendo bien por el momento 13/8/2024
function actualizarTasaDolar() {
  fetch(base_url + "Tasas/actualizarTasaDolar")
    .then((response) => response.text())
    .then((data) => {
      tblTasas.ajax.reload();
      //console.log('Tasa del dólar actualizada:', data);
      // Aquí puedes actualizar la interfaz de usuario con la nueva tasa si es necesario
    })
    .catch((error) => console.error("Error:", error));
}
*/
function ejecutarEnHoraEspecifica(hora, minuto, funcion) {
  const ahora = new Date();
  const tiempoEjecucion = new Date(
    ahora.getFullYear(),
    ahora.getMonth(),
    ahora.getDate(),
    hora,
    minuto,
    0,
    0
  );

  if (tiempoEjecucion < ahora) {
    // Si la hora especificada ya pasó hoy, programa para el día siguiente
    tiempoEjecucion.setDate(tiempoEjecucion.getDate() + 1);
  }

  const tiempoRestante = tiempoEjecucion - ahora;

  setTimeout(funcion, tiempoRestante);
}

// Usar la función para ejecutar actualizarTasaDolar a las 14:30
ejecutarEnHoraEspecifica(9, 0, actualizarTasaDolar);
ejecutarEnHoraEspecifica(15, 30, actualizarTasaDolar);

//alerta de sweetalert2
function alertas(mensaje, icono, texto = "", time = 3000) {
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
    title: mensaje,
    text: texto,
  });
}
//ninguna funcion propia de java script funciona bien para formatear numeros en "ES"
//es decir asi 1.234,56 o 989,90 siempre dan algun error
function numberFormat(number, decimals, decPoint, thousandsSep) {
  if (number == null || !isFinite(number)) {
    throw new TypeError("El número no es válido");
  }

  if (!decimals) {
    const len = number.toString().split(".").length;
    decimals = len > 1 ? len : 0;
  }

  if (!decPoint) {
    decPoint = ".";
  }

  if (!thousandsSep) {
    thousandsSep = ",";
  }

  number = parseFloat(number).toFixed(decimals);
  number = number.replace(".", decPoint);

  const splitNum = number.split(decPoint);
  splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSep);

  return splitNum.join(decPoint);
}
