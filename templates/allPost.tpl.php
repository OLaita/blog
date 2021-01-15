<?php

    include 'base.tpl.php';

    if(!isset($_SESSION['userLogged'])){
        header('Location: '.BASE.'index/login ');
    }


?>

    <main>
    
        <article>
            <h2>Todos los Post</h2>
        </article>
        <h3><?php echo $_SESSION['userLogged']; ?></h3>
    
    </main>


    <div class="container">
    
    
    <form class="" action="<?= BASE ?>blog/redirectPost" method="post">
    
        <?php include 'todosPost.tpl.php'; ?>

    </form>



<?php

include 'footer.tpl.php';

?>