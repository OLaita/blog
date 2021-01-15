<?php


$db=$this->getDB();


    if(isset($_SESSION['userLogged'])){
        echo "<div>";

        $select = $db->prepare("SELECT * FROM post where user=:user");
        $select->execute(array(':user' => $_SESSION['userLogged']));
        $result = $select->rowCount();
        $row = $select->fetchAll(\PDO::FETCH_ASSOC);

        if($result>0){
            echo "<h5>Mis Post</h5>
                <div class='d-flex'>";

            $i = 0;

            foreach($row as $rows){
                $conte = substr($rows['cont'], 0, 10).'...';

                echo "<div class='w-25 text-center border rounded-lg py-3 shadow-lg mr-3'>
                    <p>".$rows['title']."</p>
                    <p>".$conte."</p>";

                echo "<button type='submit' class='btn btn-info' name='ps[<?=$i?>]' value='".$rows['id']."'>Leer mas...</button></div>";
                $i++;
            }

            echo "</div>";

        }else{
            echo '<p>Aun no tienes post</p>';
        }
        echo "</div>";

    }

    echo "<div class='mt-5'>
        <h5>Otros Post</h5>";

        $select = $db->prepare("SELECT * FROM post where not user=:user");
        $select->execute(array(':user' => $_SESSION['userLogged']));
        $result = $select->rowCount();
        $row = $select->fetchAll(\PDO::FETCH_ASSOC);
        

        if($result>0){
            
            echo "<div class='d-flex'>";

            $i = 0;

            foreach($row as $rows){
                $btn = "<button type='submit' class='btn btn-info' name='ps[<?=$i?>]' value='".$rows['id']."'>Leer mas...</button>";

                $conte = substr($rows['cont'], 0, 10).'...';

                echo "<div class='w-25 text-center border rounded-lg py-3 shadow-lg mr-3'>
                    <p>".$rows['title']."</p>
                    <p>".$conte."</p>";

                echo $btn."</div>";
                $i++;
            }

            echo "</div>";

        }else{
            echo '<p>Aun no hay otros post</p>';
        }
        echo "</div>";

?>