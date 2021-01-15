<?php
    namespace App\Controllers;

        use App\Request;
        use App\Session;
        use App\Controller;

    final class IndexController extends Controller{

        public function __construct(Request $request,Session $session){
            parent::__construct($request,$session);
        }
        
        public function index(){
            $db=$this->getDB();
            $data=$db->selectAll('users');
            // uso de funciones declaradas en el modelo 
            // y definidas en la clase abstracta
            // $stmt=$this->query($db,"SELECT * FROM users ",null);
            $user=$this->session->get('user');
            $dataview=[ 'title'=>'Todo','user'=>$user,
                         'data'=>$data];
            $this->render($dataview);
        }

        public function login(){
            $dataview=['title'=>'Login'];
            $this->render($dataview,'login');
        }

        public function register(){
            $dataview=['title'=>'Register'];
            $this->render($dataview,'register');
        }

        public function allPost(){
            $dataview=['title'=>'allPosr'];
            $this->render($dataview,'allPost');
        }

        public function myPost(){
            $db=$this->getDB();
            $uname = $_SESSION['userLogged'];

            $select = $db->prepare("SELECT * FROM post where user=:user");
            $select->execute(array(':user' => $uname));
            $result = $select->rowCount();
            $row = $select->fetchAll(\PDO::FETCH_ASSOC);
            
            if($result>0){
                foreach($row as $rows){
                    $_SESSION['idPost'] = $rows['id'];
                    $titlePost = $rows['title'];
                    $cont = $rows['cont'];
                    $date = $rows['create_date'];
                }
            }else{
                $titlePost = null;
                $cont = null;
                $date = null;
            }

            $dataview=['title'=>'myPost', 'titlePost'=>$titlePost, 'cont' => $cont, 'date' => $date];
            $this->render($dataview,'myPost');
        }

        public function newPost(){
            $dataview=['title'=>'newPost'];
            $this->render($dataview,'newPost');
        }
       
        
    }