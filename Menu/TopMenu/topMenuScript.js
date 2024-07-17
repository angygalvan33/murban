function loadCambiarContra(username)
{
    $("#username").val(username);
    $("#contraAnt").val("");
    $("#contraNueva").val("");
    $("#contraConf").val("");
    $('#nuevaContraModal').modal('show');
    
    $("#formContra").validate().resetForm();
    $(".error").removeClass("error");
}

function guardarContra(username)
{
    var data = $("#formContra").serializeArray();
    
    var datos = {};
    datos["accion"] = "cambiarContrasena";
    datos["username"] = username;
    
    $.each(data, function(key,value) {
        datos[value.name] = value.value;
    });
        
    //guardar en bd
    $.post("./Menu/TopMenu/datos.php",datos,function(result){
        
        var msj1="";
        var msj2="";
        var msjError = "";
        
        switch(result["error"])
        {
            case 0:
                $("#successModal .modal-body").text("SE HA CAMBIADO LA CONTRASEÑA.");
                $("#successModal").modal("show");
                break;
                
            case 1:
                msjError = result["result"];
                $("#errorModal .modal-body").text("ERROR AL CAMBIAR LA CONTRASEÑA. ERROR DE BASE DE DATOS. " + msjError);
                $("#errorModal").modal("show");
                break;
                
            case 2:
                msjError = result["result"];
                $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                $("#avisosModal .modal-body").text("ERROR AL CAMBIAR LA CONTRASEÑA. " + msjError);
                $("#avisosModal").modal("show");
                break;
        }
    },"json");
}
