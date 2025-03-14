$(document).ready(function () {
    $("#btnGuardarGeneral").click(function () {
        setDatosGenerales();
    });
    $("#btnEditGeneral").click(function () {
        storeContactoGeneral();
    });
    getDatosGenerales();
    $("#idSuc").hide();
});
function storeContactoGeneral() {
    console.log("btn guardar");
    if ($("#idUser").val() != "") {
        $.ajax({
            url: base_url + "/admin/clientes/storeContactoGeneral", //archivo que recibe la peticion
            type: "POST", //método de envio
            data: {
                id: $("#idSuc").val(),
                sucursal: $("#idUser").val(),
                nombre: $("#nombre").val(),
                departamento: $("#departamento").val(),
                puesto: $("#puesto").val(),
                correo: $("#correo").val(),
                cel: $("#cel").val(),
                tel: $("#tel").val(),
                _token: $('input[name="_token"]').val(),
            },
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(response);
                alert("Registro agregado");
                getDatosGenerales();
            },
        });
    } else {
        alert("Primero tienes que seleccionar una sucursal");
    }
}
function setDatosGenerales() {
    console.log("btn guardar");
    if ($("#idUser").val() != "") {
        $.ajax({
            url: base_url + "/admin/clientes/setDatosGenerales", //archivo que recibe la peticion
            type: "POST", //método de envio
            data: {
                sucursal: $("#idUser").val(),
                nombre: $("#nombre").val(),
                departamento: $("#departamento").val(),
                puesto: $("#puesto").val(),
                correo: $("#correo").val(),
                cel: $("#cel").val(),
                tel: $("#tel").val(),
                _token: $('input[name="_token"]').val(),
            },
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(response);
                alert("Registro agregado");
                getDatosGenerales();
            },
        });
    } else {
        alert("Primero tienes que seleccionar una sucursal");
    }
}
function datosGenerales() {
    console.log("btn guardar");
    $.ajax({
        url: base_url + "/admin/clientes/datosGenerales", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idUser: $("#idUser").val(),
            telefono: $("#telefono").val(),
            correo: $("#correo").val(),
            direccion: $("#direccion").val(),
            atencion: $("#atencion").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            if (response.sw == true) {
                swal("Registro", "Datos guardados correctamente", "success");
                console.log(response);
            }
        },
    });
}
function getDatosGenerales() {
    let tabla = document.getElementById("tabGenerales");
    let tab = "";
    $.ajax({
        type: "POST",
        url: base_url + "/admin/clientes/getDatosGenerales", //archivo que recibe la peticion
        data: {
            id: $("#idUser").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            
            console.log(response);
            model = response.model;
            tab += '<table class="table table-sm" style="font-size:10px">';
            tab += '    <thead class="thead-dark">';
            tab += "        <tr>";
            tab += "          <th>#</th>";
            tab += "          <th>Nombre</th>";
            tab += "          <th>Departamento</th>";
            tab += "          <th>Puesto</th>";
            tab += "          <th>Email</th>";
            tab += "          <th>Celular</th>";
            tab += "          <th>Telefono</th>";
            tab += "          <th></th>";
            tab += "        </tr>";
            tab += "    </thead>";
            tab += "    <tbody>";
            $.each(response.model, function (key, item) {
                tab += "<tr>";
                tab += "<td>" + item.Id_contacto + "</td>";
                tab += "<td>" + item.Nombre + "</td>";
                tab += "<td>" + item.Departamento + "</td>";
                tab += "<td>" + item.Puesto + "</td>";
                tab += "<td>" + item.Celular + "</td>";
                tab += "<td>" + item.Telefono + "</td>";
                tab +=
                    '<td><button class="btn btn-info" onclick="getContactoGeneral(' +
                    item.Id_contacto +
                    ')" data-toggle="modal" data-target="#modalGenerales"><i class="fas fa-edit"></i></button></td>';
                tab += "</tr>";
            });
            tab += "    </tbody>";
            tab += "</table>";
            tabla.innerHTML = tab;
        },
    });
}
function getContactoGeneral(id) {
    console.log("btn guardar");
    $.ajax({
        url: base_url + "/admin/clientes/getContactoGeneral", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            id: id,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            $("#idSuc").val(id);
            $("#nombre").val(response.model.Nombre);
            $("#departamento").val(response.model.Departamento);
            $("#puesto").val(response.model.Puesto);
            $("#correo").val(response.model.Email);
            $("#cel").val(response.model.Celular);
            $("#tel").val(response.model.Telefono);

            $("#btnEditGeneral").show();
            $("#btnGuardarGeneral").hide();
        },
    });
}
function setModal() {
    $("#btnEditGeneral").hide();
    $("#btnGuardarGeneral").show();
}
