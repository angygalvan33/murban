function getTotalesCobro() {
    var totalSinFac = 0;
    var totalConFac = 0;
    var totalConSinFac = 0;
    var datos = { "accion":'totalCobrarConSinFactura' };

    $.post("./pages/cuentasPorCobrar/datos.php", datos, function(result) {
        totalSinFac = result.totalCobrarSinFactura;
        if (totalSinFac === null)
            totalSinFac = 0;

        totalConFac = result.totalCobrarConFactura;
        if (totalConFac === null)
            totalConFac = 0;
        
        totalConSinFac = result.totalCobrarConSinFactura;
        if (totalConSinFac === null)
            totalConSinFac = 0;
        
        $("#dTotalCobrarSinFac").html("<h4>OC no facturadas:&nbsp;<strong>$"+ formatNumber(totalSinFac) +"<strong></h4>");
        $("#dTotalCobrarConFac").html("<h4>OC facturadas:&nbsp;<strong>$"+ formatNumber(totalConFac) +"<strong></h4>");
        $("#dTotalCobro").html("<h4>Total a cobrar:&nbsp;<strong>$"+ formatNumber(totalConSinFac) +"<strong></h4>");
    }, "json");
}