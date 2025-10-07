<?php
	$local_python = "python ";
	$local_script_python = "c:\\Apache24\\htdocs\\educacao\\python\\";
	$nome_script_python = "test2.py";
	$comando = escapeshellcmd($local_python.$local_script_python.$nome_script_python);	
    $resultado = shell_exec($comando);
    echo $resultado;
?>