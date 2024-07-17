var tfactura = 0;
var foliosFacturas = [];

function loadDataTableReembolsarFacturas() {
    $('#reembolsarFacturasTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'responsive':true,
        'ajax': {
            url: "pages/cajaChica/administracion/reembolsarFacturas/reembolsarFacturasData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".reembolsarFacturasTable-error").html("");
                $("#reembolsarFacturasTable").append('<tbody class="reembolsarFacturasTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#reembolsarFacturasTable").css("display", "none");
            },
            data: {
                "IdUsuario": $(".detalles").attr("id")
            }
        },
        'columns': [
            { 'data': "Creado", orderable: true, width: "10%" },
            { 'data': "Material", orderable: true, width: "30%" },
            { 'data': "FolioFactura", orderable: true, width: "20%" },
            { 'data': "Total", orderable: true, width: "15%",
                mRender: function (data, type, row) {
                    return "$"+ parseFloat(row.Total).toFixed(2);
                }
            },
            { width: "40%",
                mRender: function (data, type, row) {
                    return "<input id='pagarFacturaReembolso' class='pagarFacturaReembolso icheckbox_flat-green' checked type='checkbox'>";
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        },
        'footerCallback': function (row, data, start, end, display) {
            var api = this.api(), data;
            var cont = 0;

            $.each(data, function(key, value) {
                foliosFacturas.push(value.IdCajaChicaDetalle);
                cont += parseFloat(value.Total);
            });
            
            $("#tref").html("<h4>Total:&nbsp;<strong>$"+ cont.toFixed(2) +"<strong></h4>");
            tfactura = cont;
        }
    });
}

function pagarFacturadas() {
    if (foliosFacturas.length != 0) {
        var datos = {};
        datos["accion"] = "pagarFacturadas";
        datos["IdCajaChica"] = $(".detalles").attr("id");
        datos["folios"] = foliosFacturas;
        //guardar en bd
        $.post("./pages/cajaChica/administracion/reembolsarFacturas/datos.php", datos, function(result) {
            switch (result["error"]) {
                case 0:
                    $("#successModal .modal-body").text("SE HA REEMBOLSADO CON ÉXITO.");
                    $("#successModal").modal("show");
                    $('#adminCChTable').DataTable().ajax.reload();
                break;
                case 1:
                    $("#errorModal .modal-body").text("ERROR AL REEMBOLSAR");
                    $("#errorModal").modal("show");
                break;
            }
        }, "json");
    }
}