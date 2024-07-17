<?php
include_once 'Math/BigInteger.php';

class Permisos {
    public function acceso($valor, $permisosUsuario) {
        $val = new Math_BigInteger($valor, 10);
        $permisos = new Math_BigInteger($permisosUsuario, 10);
        $and = $val->bitwise_and($permisos);
        
        return ($and->compare($val)) == 0;
    }
}
