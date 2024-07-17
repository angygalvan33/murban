function getTotalesCxP() {
    var datos = { "accion":'obtenerDeudaTotal' };

    $.post("./pages/cuentasPorPagar/general/datos.php", datos, function(result) {
        cont = result;
        if (cont === null) {
            cont = 0;
        }
        
        $("#dt").html("<h4>Deuda total:&nbsp;<strong>$"+ formatNumber(cont) +"<strong></h4>");
    }, "json");
    
    var datos = { "accion":'obtenerDeudaPropuesta' };

    $.post("./pages/cuentasPorPagar/general/datos.php", datos, function(result) {
        tpropuesto = result;
        if (tpropuesto === null) {
            tpropuesto = 0;
        }
        
        $("#dtpropuesto").html("<h4>Total Propuesto:&nbsp;<strong>$"+ formatNumber(tpropuesto) +"<strong></h4>");
    }, "json");
    
    var datos = { "accion":'obtenerDeudaAutorizada' };

    $.post("./pages/cuentasPorPagar/general/datos.php", datos, function(result) {
        tautorizado = result;
        if (tautorizado === null) {
            tautorizado = 0;
        }
        
        $("#dtautorizado").html("<h4>Total Autorizado:&nbsp;<strong>$"+ formatNumber(tautorizado) +"<strong></h4>");
    }, "json");
}