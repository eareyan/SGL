<?php
//Incluir el archivo base.php, donde esta la logica de conexion con la base de datos 
require 'base.php';
//Enviar email de pagos
library_gestion_reportes::enviarEmailPagos('terminal',true /* Esto debe estar en false en ambiente de produccion*/);
