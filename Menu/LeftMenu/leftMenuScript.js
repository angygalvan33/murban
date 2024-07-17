function formatNumber (num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

function loadUsuarios() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/usuarios/usuarios.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadOrdenesCompra() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/compras/ordenesCompra.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadProveedores() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/proveedores/proveedores.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadMetodosPago() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/metodoPago/metodoPago.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadMateriales() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/material/material.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadCategorias() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/categoria/categoria.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadObras() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/obras/obra.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadCuentasPorPagar() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/cuentasPorPagar/cuentasPorPagar.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadDetallePagos() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/detallePagos/detallePagos.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadCajaChica() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/cajaChica/cajaChica.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadRequisiciones() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/requisiciones/requisiciones.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadPersonal() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/personal/personal.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadAlmacen() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/almacen/almacen.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadPanelAdmin() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/panelAdmin/panelAdmin.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadArticulos() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/articulos/articulosGeneral.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadMaterialesEnPrestamo() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/materialesEnPrestamo/materialesEnPrestamo.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadUbicaciones() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/almacen/ubicaciones.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadConfiguracion() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/almacen/configuracion.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadConcentradoOrdenesCompra() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/compras/concentrado/ocs.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadClientes() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/clientes/clientes.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

function loadCuentasPorCobrar() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/cuentasPorCobrar/cuentasPorCobrar.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}

/*function loadProducts() {
    $("#loading").modal("show");
    
    setTimeout( function() {
        $(".content").load("pages/articulos/articulosGeneral.php", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") //mostrar vista de error
                alert("Error: "+ xhr.status +": "+ xhr.statusText);
            $("#loading").modal("hide");
        });
    }, 1000);
}*/