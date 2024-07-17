function loadClientesDataTable(r) {
    $('#clienteTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/clientes/clientesData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".clienteTable-error").html("");
                $("#clienteTable").append('<tbody class="clienteTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#clienteTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Nombre", orderable: true, width: "15%", className: 'details-control' },
            { 'data': "Direccion", orderable: true, width: "25%" },
            { 'data': "Rfc", orderable: true, width: "10%" },
            { 'data': "TipoPersona", orderable: true, width: "10%" },
            { 'data': "DiasCredito", orderable: true, width: "10%" },
            { 'data': "LimiteCredito", orderable: true, width: "10%",
                mRender: function (data, type, row) {
                    var color = "black";
                    var cd = row.CreditoDisponible;
                    if (cd == 0)
                        color="red";

                    var result = "<p style='color:"+ color +"'><strong>" + "$"+ formatNumber(parseFloat(row.LimiteCredito).toFixed(2)) +"</strong></p>"
                    return result;
                 }
			},
            { orderable: false, width: "20%",
                mRender: function (data, type, row) {
                    var buttons = "";
                    buttons += "<button type='button' id='editar' style='margin-right:5px' class='btn btn-success btn-sm'><i class='fa fa-edit'></i>&nbsp;Editar</button>";
                    buttons += "<button type='button' id='eliminar' style='margin-right:5px' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i>&nbsp;Eliminar</button>";
                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function resetValues() {
    contactos = [];
    $('#contactoTable').DataTable().clear().draw();
    muestraFormContacto(0);
    $('input:radio[name="tipoPersona"]').prop('checked', false);
    $("#nombre").val("");
    $("#direccion").val("");
    $("#rfc").val("");
    $("#tipoPersona").val("");
    $("#diasCredito").val("");
    $("#limiteCredito").val("");
    $("#formCliente" ).validate().resetForm();
    $("#formCliente :input").removeClass('error');
}

function openModalCliente() {
    resetValues();
    $("#accion").val(0);
    $("#idRegistro").val(0);
    $('#nuevoClienteModal').modal('show');
}
//accion 0 => guardar, 1 => editar
//idRegistro en editar trae el Id del que se eliminará, en alta viene con 0
function guardarCliente(accion, idRegistro) {
    var continua = true;
    var data = $("#formCliente").serializeArray();
    var datos = {};
    datos["accion"] = accion === "0" ? "alta" : "editar";
    datos["id"] = idRegistro;
    var datosContacto = [];
    
    $.each(data, function(key, value) {
        if (value.name === "limiteCredito")
            datos[value.name] = value.value.replace(/\,/g, '');
        else
            datos[value.name] = value.value;
    });

    $('#contactoTable').DataTable().rows().every(function () {
        var d = this.data();
        var nuevo = {};
        nuevo["Nombre"] = d.Nombre;
        nuevo["Email"] = d.Email;
        nuevo["Telefono"] = d.Telefono;
        datosContacto.push(nuevo);
    });

    if (datosContacto.length === 0)
        continua = false;

    if (continua === true) {
        datos["tipoPersona"] = datos["tipoPersona"] === "0" ? "Física" : "Moral";
        datos["telefonoc"] = datos["emailc"] = ""; //estos datos no importan
        datos["telefono"] = datos["email"] = ""; //estos datos no importan
        
        var i = 0;
        var cont = datosContacto.length;
        var contactos = "[";
        $.each (datosContacto, function(key, value) {
            i++;
            contactos += "{'Nombre':" + "'"+ value.Nombre +"',";
            contactos += "'Email':" + "'"+ value.Email +"',";
            contactos += "'Telefono':" + "'"+ value.Telefono +"'}";
            
            if (cont !== i)
                contactos += ",";
        });

        contactos += "]";
        contactos = contactos.replace(/'/g, '"');
        datos["contactos"] = contactos;
        //guardar en bd
        $.post("./pages/clientes/datos.php", datos, function(result) {
            $('#clienteTable').DataTable().ajax.reload();
            switch (result["error"]) {
                case 0:
                    $('#nuevoClienteModal').modal('hide');
                    $("#successModal .modal-body").text(result["result"]);
                    $("#successModal").modal("show");
                break;
                case 1:
                    $("#errorModal .modal-body").text(result["result"]);
                    $("#errorModal").modal("show");
                break;
                case 2:
                    $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                    $("#avisosModal .modal-body").text(result["result"]);
                    $("#avisosModal").modal("show");
                break;
            }
        }, "json");
    }
}

function loadEditarCliente(data) {
    $('#contactoTable').DataTable().clear().draw();
    if (data.TipoPersona === "Física") {
        $('#pfisica').prop('checked', true);
        $('#pmoral').prop('checked', false);
        $("#email").val(data.Email);
        $("#telefono").val(data.Telefono);
		muestraFormContacto(0);
		var datos = $.parseJSON(data.Contactos);
        $.each(datos,function(i, val) {
            var nuevo = {};
            nuevo["Nombre"] = val.Nombre;
            nuevo["Email"] = val.Email;
            nuevo["Telefono"] = val.Telefono;
            $('#contactoTable').DataTable().row.add(nuevo).draw();
        });
    }
    else {
        muestraFormContacto(0);
        $('#pfisica').prop('checked', false);
        $('#pmoral').prop('checked', true);
        var datos = $.parseJSON(data.Contactos);

        $.each(datos, function(i, val) {
            var nuevo = {};
            nuevo["Nombre"] = val.Nombre;
            nuevo["Email"] = val.Email;
            nuevo["Telefono"] = val.Telefono;
            $('#contactoTable').DataTable().row.add(nuevo).draw();
        });
    }
    
    $("#nombre").val(data.Nombre);
    $("#direccion").val(data.Direccion);
    $("#rfc").val(data.Rfc);
    $("#tipoPersona").val(data.TipoPersona);
    $("#diasCredito").val(data.DiasCredito);
    $("#limiteCredito").val(data.LimiteCredito);
    $("#btnAceptar").prop("disabled", false);
    $("#accion").val(1);
    $("#idRegistro").val(data.IdCliente);
    $('#nuevoClienteModal').modal('show');
    $("#formCliente").validate().resetForm();
    $(".error").removeClass("error");
}

function eliminarCliente(idCliente) {
    //eliminacion en bd
    var datos = { "accion":'baja', "id":idCliente };
    
    $.post("./pages/clientes/datos.php", datos, function(result) {
        $('#clienteTable').DataTable().ajax.reload();
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
            break;
            case 1:
                $("#errorModal .modal-body").text(result["result"]);
                $("#errorModal").modal("show");
            break;
            case 2:
                $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                $("#avisosModal .modal-body").text(result["result"]);
                $("#avisosModal").modal("show");
            break;
        }
    }, "json");
}

function eliminarRegistro(id, tipo) {
    eliminarCliente(id);
}
//modal cliente
function inicializaContactoTabla() {
    $('#contactoTable').DataTable( {
        'lengthMenu': [ [5,10, 15, -1], [5,10, 15, "Todos"] ],
        'data': contactos,
        'columns': [
            { 'data': "Nombre", sortable: false, width: "35%", orderable: false },
            { 'data': "Email", sortable: false, width: "25%", orderable: true },
            { 'data': "Telefono", sortable: false, width: "20%", orderable: true },
            {
                mRender: function (data, type, row) {
                    var button = "<button type='button' id='eliminarContacto' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i></button>";
                    return button;
                 }, width: "20%",sortable: false,
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function nuevoContacto(nombre, email, telefono) {
    if (nombre !== null && nombre !== "" && email !== null && email !== "" && telefono !== null && telefono !== "") {
        var nuevo = {};
        nuevo["Nombre"] = nombre;
        nuevo["Email"] = email;
        nuevo["Telefono"] = telefono;

        $('#contactoTable').DataTable().row.add(nuevo).draw();
        $('#contacto').val("");
        $('#emailc').val("");
        $('#telefonoc').val("");
        muestraFormContacto(0);
    }
}