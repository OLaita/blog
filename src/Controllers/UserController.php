<?php

namespace App\Controllers;

use App\View;
use App\Model;
use App\Request;
use App\Controller;
use App\DB;
use App\Session;

    class UserController extends Controller implements View,Model{

        public function __construct(Request $request, Session $session){
            parent::__construct($request, $session);
        }

        function logout(){
            session_destroy();
            $_COOKIE['name'] == "";
            $_COOKIE['passwd'] == "";
            header('Location: '.BASE.'');
        }

        function insertItems($db,$email,$user,$pass){

            $stmt = $db->prepare("INSERT INTO users (email, uname, passw, role) VALUES (:email,:uname,:passw, 1)");
    
            if($stmt->execute([':email'=>$email, ':uname'=>$user, ':passw'=>$pass]) ){
                $mostrar = setcookie("mostrar", "USUARIO REGISTRADO");
                header('Location: '.BASE.'index/login');
                //echo "Hola";
            }
            echo "Hola";
    
        }

        function newPost(){
            $db=$this->getDB();

            $date = date("Y-m-d");
            $title = filter_input(INPUT_POST, "title");
            $cont = filter_input(INPUT_POST, "description");
            echo $date;
            $select = $db->prepare("INSERT INTO post (title, cont, user, create_date) VALUES (:title,:cont,:user,:create_date)");

            if($select->execute(['title' => $title, 'cont' => $cont, ':user' => $_SESSION['userLogged'], 'create_date' => $date])){
                echo "Hola";
                header('Location: '.BASE.'index/myPost');
            }
            echo "adios";
        }

        function buscarUsuarios($db, $email, $user, $pass){

            $reg = filter_input(INPUT_POST, "reg");
            $save = filter_input(INPUT_POST, "save");
    
            $select = $db->prepare("SELECT * FROM users WHERE uname=:uname LIMIT 1");
            $select->execute(array(':uname' => $user));
            $result = $select->rowCount();
            $row = $select->fetchAll(\PDO::FETCH_ASSOC);
            if ($result > 0) {
                if(isset($reg)){
                    setcookie("regis", "USUARIO EXISTENTE",time()+60,'/');
                    header('Location: '.BASE.'index/register');
                    echo "registro";
                }else{
                    $usuario = $row[0];
                    $passV = password_verify($pass, $usuario['passw']);
                    if($passV){
                        setcookie("mostrar", "SESION INICIADA");
                        $_SESSION["userId"] = $usuario['id'];
                        $_SESSION["userLogged"] = $usuario['uname'];
                        header('Location: '.BASE.'index/myPost');
                    }else{
                        setcookie("mostrar","Usuario o contraseña equivocados",time()+60,'/');
                        header('Location: '.BASE.'index/login');
                    }
                }
                
            }else {
                if(isset($reg)){
                    $passH = password_hash($pass, PASSWORD_BCRYPT, ["cost"=>4]);
                    self::insertItems($db, $email, $user, $passH);
                    
                }else{
                    setcookie("mostrar", "NO EXISTE EL USUARIO",time()+60,'/');
                    header('Location: '.BASE.'index/login');
                    echo "login";
                }
                    
            }
    
        }

        public function login_register(){

            $db = DB::singleton();
            
            $correo = filter_input(INPUT_POST, "correo");
            $correo = $correo."@correo.com";
            $name = filter_input(INPUT_POST, "name");
            $pass = filter_input(INPUT_POST, "pass");
            $pass2 = filter_input(INPUT_POST, "pass2");

            $save = filter_input(INPUT_POST, "save");
            $reg = filter_input(INPUT_POST, "reg");

            if(isset($_COOKIE["name"]) && isset($_COOKIE["passwd"])){
                $name = $_COOKIE["name"];
                $pass = $_COOKIE["passwd"];
            }

            if($name != null && $pass != null){

                if(isset($reg)){
                    
                    if($pass == $pass2){
                        
                        self::buscarUsuarios($db, $correo, $name, $pass);
                        
                    }else{
                        setcookie("mostrar", "Las contraseñas no coinciden", time()+2, '/');
                        header('Location: '.BASE.'index/register');
                    }
                }else{
                    if(isset($save)){
                        setcookie("name", $name,time()+60,'/');
                        $passH = password_hash($pass, PASSWORD_BCRYPT, ["cost"=>4]);
                        setcookie("passwd", $pass, time()+60, '/');
                        $_SESSION["nameSES"] = $name;
                        $_SESSION["passwdSES"] = $pass;
                    }
                    self::buscarUsuarios($db, $correo, $name, $pass);
                }
            
            }else{
                setcookie("mostrar", "El usuario o contraseña vacios", time()+2, '/');
                header('Location: '.BASE.'index/login');
            }
        }

        function deleteUser(){

            $db = DB::singleton();

            $id = $_SESSION['userId'];

            $delU = $db->prepare("DELETE FROM users WHERE id=:id LIMIT 1");
            $delU->execute(array(':id' => $id));

            $result = $db->prepare('SELECT * FROM task where user=:id');
            $result->execute(array('id' => $id));

            foreach($result as $row){
                $_SESSION['idT'] = $row['id'];
            }

            $delT = $db->prepare("DELETE FROM task WHERE user=:id LIMIT 1");
            $delT->execute(array(':id' => $id));

            $delT = $db->prepare("DELETE FROM task_item WHERE task=:idTask LIMIT 1");
            $delT->execute(array(':idTask' => $_SESSION['idT']));

            header('Location: '.BASE.'');
        }
        

    }