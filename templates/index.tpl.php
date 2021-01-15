<?php
    include 'base.tpl.php';
    setcookie('mostrar', "Iniciar sesion",time()+60,'/');
    setcookie('regis', "Formulario de registro",time()+60,'/');

?>


<main>
    
    <article>
        <h2><?= $title;?></h2>
    </article>
    
</main>



<?php
    include 'footer.tpl.php';
?>