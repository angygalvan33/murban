function eliminarRegistro(id, tipo) {
    if (tipo == 0)
        eliminarLinea(id);
    else if (tipo == 1)
        eliminarArticulo(id);
}