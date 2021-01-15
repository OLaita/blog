<?php
$db=$this->getDB();

    if(isset($titlePost)){
        $mio = "";
        if(isset($_SESSION["myPost"])){
            if($_SESSION["myPost"] == true){
                $mio = "<form class='position-absolute' action='".BASE."blog/actionPost' method='post'>
                        <button class='btn' type='submit' name='ed[<?=1?>]' value='".$_SESSION['idPost']."''>
                            <img src='".BASE."public/Imagenes/editar.svg' alt='Submit' width='48' height='48'>
                        </button>
                        <button class='btn' type='submit' name='del[<?=1?>]' value='".$_SESSION['idPost']."''>
                            <img src='".BASE."public/Imagenes/basura.svg' alt='Submit' width='48' height='48'>
                        </button></form>"; 
            }
        }
        echo '<div class="container p-3 my-3 border text-center w-100">
                '.$mio.'
                <h1 class="h1">'.$titlePost.'</h1>
                <p class="text-muted">'.$date.'</p>
                <p>'.$cont.'</p>
            </div>';

        echo '<div class="container p-3 my-3 bg-primary text-white text-center w-100">
                <h4>Comentarios</h4>
                <form class="d-flex justify-content-cente align-items-center flex-column" action="'.BASE.'blog/newComment" method="post">
                    <div class="col-md-6 text-center form-group">
                        <label for="name">Pon un comentario:</label>
                        <textarea maxlength="255" required class="form-control" rows="2" id="comment" name="comment"></textarea>
                    </div>
                    <button type="submit" name="newPost" class="btn btn-primary bg-dark">Submit</button>
                </form>
                <div>';

        $select = $db->prepare("SELECT * FROM comments where post=:post order by date_time desc");
        $select->execute(array(':post' => $_SESSION['idPost']));
        $result = $select->rowCount();
        $row = $select->fetchAll(\PDO::FETCH_ASSOC);

        if($result >0){

            $time = getdate();

            $año = $time['year'];
            $mes = $time['mon'];
            $dia = $time['mday'];
            $hor = $time['hours'];
            $min = $time['minutes'];
            $sec = $time['seconds'];

            $fechafinal =  $año."-".$mes."-".$dia." ".$hor.":".$min.":".$sec;

            $first_date = new DateTime($fechafinal);
            foreach($row as $rows){
                
                $second_date = new DateTime($rows['date_time']);
                
                $difference = $first_date->diff($second_date);
                $time = format_interval($difference);
                
                if(format_interval($difference) == null){
                    $time = "0 sec";
                }

                echo '<div class="container p-3 my-3 bg-dark text-white text-left">
                    <div class="d-flex justify-content-between"><span>'.$rows['user'].'</span>
                    <span class="text-muted">Hace: '.$time.'</span></div><br>
                    <p>'.$rows['comment'].'</p></div>';

            }

        }else{
            echo '<p>Aun no hay comentarios</p>';
        }

            echo '</div></div>';

    }else{
        header('Location: '.BASE.'index/allPost');
    }




    function format_interval(DateInterval $interval) {
        $result = "";
        if ($interval->y) { $result .= $interval->format("%y años "); }
        if ($interval->m) { $result .= $interval->format("%m meses "); }
        if ($interval->d) { $result .= $interval->format("%d dias "); }
        if ($interval->h) { $result .= $interval->format("%h horas "); }
        if ($interval->i) { $result .= $interval->format("%i min "); }
        if ($interval->s) { $result .= $interval->format("%s sec "); }
    
        return $result;
    }
    
    

?>

