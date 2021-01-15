<?php

    include 'base.tpl.php';

?>

    <main>
    
        <article>
            <h2>Editar Tarea</h2>
        </article>
    
    </main>


    <div class="container">
    
    
    <form class="d-flex justify-content-cente align-items-center flex-column" action="<?= BASE ?>blog/updatePost" method="post">

    <div class="col-md-6 text-center form-group">
            <label for="name">Title Post:</label>
            <input maxlength="100" required type="text" class="form-control text-center" placeholder="<?php echo $titlePost ?>" id="name" name="tn">
        </div>
        <div class="col-md-6 text-center form-group">
            <label for="date">Contenido:</label>
            <textarea maxlength="255" required class="form-control" rows="3" id="content" name="content"><?php echo $cont ?></textarea>
        </div>
        <button type="submit" name="cambio" class="btn btn-primary">Submit</button>
    

    </form>

    
    
    </div>


<?php

include 'footer.tpl.php';

?>