<?php

    include 'base.tpl.php';

    if(!isset($_SESSION['userLogged'])){
        header('Location: '.BASE.'index/login ');
    }

    if(isset($date)){
        $d = (int)substr($date, 8);
        $m = (int)substr($date, 5,2);
        $y = (int)substr($date, 0,4);
        $fecha = date("M, d", mktime(0, 0, 0, $m, $d, $y));
    }


?>

    
    <?php
        include 'post.tpl.php'
    ?>



<?php

include 'footer.tpl.php';

?>