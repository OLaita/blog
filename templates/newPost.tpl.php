<?php

    include 'base.tpl.php';


?>

    <main>
    
        <article>
            <h2>Nuevo Post</h2>
        </article>
        <h3><?php echo $_SESSION['userLogged']; ?></h3>
    
    </main>

    

    <div>
    
    <h4>Crear un post</h4>

    <form class="d-flex justify-content-cente align-items-center flex-column" action="<?= BASE ?>user/newPost" method="post">
        <div class="col-md-4 text-center form-group">
            <label for="name">Titulo:</label>
            <input type="text" class="form-control text-center" placeholder="Enter title" id="title" name="title">
        </div>
        <div class="col-md-6 text-center form-group">
            <label for="name">Contenido:</label>
            <textarea class="form-control" rows="3" id="description" name="description"></textarea>
        </div>
        <!--<div class="col-md-6 text-center form-group">
            <label for="pwd">Fecha:</label>
            <input type="date" class="form-control text-center" id="date" value="<?php echo date("Y-m-d");?>" name="date">
        </div>-->
        <button type="submit" name="newPost" class="btn btn-primary">Submit</button>

    </form>
    
    
    </div>


<?php

include 'footer.tpl.php';

?>