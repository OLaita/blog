<?php

namespace App\Controllers;

use App\View;
use App\Model;
use App\Request;
use App\Controller;
use App\DB;
use App\Session;

    class BlogController extends Controller implements View,Model{

        public function __construct(Request $request, Session $session){
            parent::__construct($request, $session);
        }

        function newPost(){
            $db=$this->getDB();

            $date = date("Y-m-d");
            $title = filter_input(INPUT_POST, "title");
            $cont = filter_input(INPUT_POST, "description");

            $select = $db->prepare("INSERT INTO post (title, cont, user, create-date) VALUES (:title,:cont,:user,:date)");

            if($select->execute(['title' => $title, 'cont' => $cont, ':user' => $_SESSION['userLogged'], 'date' => $date])){
                header('Location: '.BASE.'index/myPost');
            }

            echo "Hola";
        }

        function newComment(){
            $db=$this->getDB();

            $com = filter_input(INPUT_POST, "comment");

            $time = getdate();

            $año = $time['year'];
            $mes = $time['mon'];
            $dia = $time['mday'];
            $hor = $time['hours'];
            $min = $time['minutes'];
            $sec = $time['seconds'];

            $fechafinal =  $año."-".$mes."-".$dia." ".$hor.":".$min.":".$sec;


            $select = $db->prepare("INSERT INTO comments (comment, user,date_time, post) VALUES (:comment,:user,:date_time,:post)");

            if($select->execute(['comment' => $com, 'user' =>$_SESSION["userLogged"],'date_time' => $fechafinal, 'post' => $_SESSION['idPost']])){
                header('Location: '.BASE.'blog/redirectPost');
            }

        }

        function redirectPost(){
            $db=$this->getDB();

            if(isset($_POST['ps'])) {
                foreach ($_POST['ps'] as $key => $value) {
                    $idP = $value;
                }

                $select = $db->prepare("SELECT * FROM post where id=:id");
                $select->execute(array(':id' => $idP));
                
                self::plantillaQuery($select);

            }else{

                $select = $db->prepare("SELECT * FROM post where id=:id");
                $select->execute(array(':id' => $_SESSION['idPost']));
                
                self::plantillaQuery($select);

            }
        }

        function plantillaQuery($select){
            $result = $select->rowCount();
            $row = $select->fetchAll(\PDO::FETCH_ASSOC);
            $userLog = $_SESSION["userLogged"];
                
            if($result>0){
                foreach($row as $rows){
                    $_SESSION['idPost'] = $rows['id'];
                    $titlePost = $rows['title'];
                    $cont = $rows['cont'];
                    $date = $rows['create_date'];
                    if($rows['modify_date'] != null){
                        $date = $rows['modify_date'];
                    }
                    $_SESSION["myPost"] = false;

                    if($userLog == $rows['user']){
                        $_SESSION["myPost"] = true;
                    }

                }
            }else{
                $titlePost = null;
                $cont = null;
                $date = null;
                $_SESSION["myPost"] = false;
            }

            $dataview=['title'=>$titlePost, 'titlePost'=>$titlePost, 'cont' => $cont, 'date' => $date];
            $this->render($dataview,'oPost');
        }

        function updatePost(){
            $db=$this->getDB();

            $title = filter_input(INPUT_POST,'tn');
            $title = trim($title);
            if($title == null){
                $title = $_SESSION['tp'];
            }

            $cont = filter_input(INPUT_POST,'content');
            $cont = trim($cont);
            if($cont == null){
                $cont = $_SESSION['cp'];
            }

            $time = getdate();

            $año = $time['year'];
            $mes = $time['mon'];
            $dia = $time['mday'];

            $fechafinal =  $año."-".$mes."-".$dia;

            $select = $db->prepare("UPDATE post SET title=:title, cont=:cont, modify_date=:modify_date WHERE id=:id");
            
            if($select->execute([':title'=>$title, ':cont'=>$cont, 'modify_date' => $fechafinal, ':id'=>$_SESSION['idPost']])){
                self::redirectPost();
            }else{
                echo "Hola";
            }
        }

        function deletePost(){
            $db=$this->getDB();

            $command3 = $db->prepare("DELETE FROM post WHERE id=:id");
            $command4 = $db->prepare("DELETE FROM comments WHERE post=:post");
            try{
                $command4->execute([':post'=>$_SESSION['idPost']]);
                $command3->execute([':id'=>$_SESSION['idPost']]);
                header('Location: '.BASE.'index/allPost');
            }catch(PDOException $e){
                die($e->getMessage());
            }

        }

        function actionPost(){
            $db=$this->getDB();

            if(isset($_POST['ed'])) {
                foreach ($_POST['ed'] as $key => $value) {

                    $idP = $value;
                    $select = $db->prepare("SELECT * FROM post where id=:id");
                    $select->execute(array(':id' => $idP));
                    $row = $select->fetchAll(\PDO::FETCH_ASSOC);

                    foreach($row as $rows){
                        $titlePost = $rows['title'];
                        $cont = $rows['cont'];
                        $_SESSION['tp'] = $titlePost;
                        $_SESSION['cp'] = $cont;
                    }
                    
                    $dataview=['title'=>"ModifyPost", 'titlePost'=>$titlePost, 'cont' => $cont];
                    $this->render($dataview,'udPost');
                }
            }

            if(isset($_POST['del'])) {
                foreach ($_POST['del'] as $key => $value) {
                    $_SESSION['idPost'] = $value;
                    self::deletePost();
                }
            }

        }

    }