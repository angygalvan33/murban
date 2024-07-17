$( document ).ready(function() {
    cargarLogo();
    leerDatosEmpresa();
});

function leerDatosEmpresa() {
    var datos = {};
    datos["accion"] = "leerDatos";
    //console.log(datos);
    $.post("./pages/panelAdmin/datos.php",datos,function(result){
        //console.log("entra");
        $("#nombreEmpresa").text(result.result["nombreEmpresa"]);
    }, "json");
}

function cargarLogo() {
    var formData = new FormData();
    
    formData.append("accion", "obtenerLogo");
        
    $.ajax({
        type: 'POST',
        url: './pages/panelAdmin/datos.php',
        data: formData,
        success: function(result) {
            if(result != false)
            {
                $("#logo").attr("src","./cxp" + result);
            }
        },
        error: function(response) {

        },
        processData: false,
        contentType: false
    });
}