<?php

namespace App\Controllers;

use App\Controller;
use App\Session;
use App\Request;


class UserController extends Controller
{

    public function __construct(Request $request, Session $session)
    {
        parent::__construct($request, $session);
    }

    //url = user/login
    function login()
    {
        $user=$this->session->get('user');
        $this->render(['user'=>$user],'login');
    }
    
/**
 * return user dashboard
 *
 * @return void
 */
    public function dashboard()
    {
        $user = $this->session->get('user');
        $data = $this->getDB()->selectWhereWithJoin('tasks', 'users', ['tasks.id', 'tasks.description','tasks.due_date'], 'user', 'id', ['users.uname', $user['uname']]);
        $this->render(['user' => $user, 'data' => $data], 'dashboard');
    }

    public function register(){
        $user=$this->session->get('user');
        $this->render(['user'=>$user],'register');
    }
    public function profile(){
        $user=$this->session->get('user');
        $this->render(['user'=>$user],'profile');            

    }
    function logout()
        {
            session_destroy();
            header('Location:'.BASE);
        }
    function log()
    {
        //action="user/log"
        //obtener $POSTs
        //autenticar

        if (isset($_POST['email']) && !empty($_POST['email'])
            && isset($_POST['passw']) && !empty($_POST['passw'])) {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $pass = filter_input(INPUT_POST, 'passw', FILTER_SANITIZE_STRING);

            $user = $this->auth($email, $pass);
            if ($user) {
                $this->session->set('user', $user);
                //si usuari valid
                if (isset($_POST['remember-me']) && ($_POST['remember-me'] == 'on' || $_POST['remember-me'] == '1') && !isset($_COOKIES['remember'])) {
                    $hour = time() + 3600 * 24 * 30;
                    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                    setcookie('uname', $user['uname'], $hour, $path);
                    setcookie('email', $user['email'], $hour, $path);
                    setcookie('active', 1, $hour, $path);
                }
                header('Location:'.BASE.'user/dashboard');
            } else {
                header('Location:'.BASE.'user/login');
            }
        }
    }

    public function reg(){
        if(isset($_POST['email'])&&!empty($_POST['email'])&&
        isset($_POST['uname'])&&!empty($_POST['uname'])&&
        isset($_POST['passw'])&&!empty($_POST['passw']))
        {
            
            $email=filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
            $passw=filter_input(INPUT_POST,'passw',FILTER_SANITIZE_STRING);
            $passw2=filter_input(INPUT_POST,'passw2',FILTER_SANITIZE_STRING);
            $uname=filter_input(INPUT_POST,'uname',FILTER_SANITIZE_STRING);
            if ($passw===$passw2){
                
                $passw=password_hash($passw,PASSWORD_BCRYPT,['cost'=>4]);
                $data=[
                    'email'=>$email,
                    'uname'=>$uname,
                    'passw'=>$passw,
                    'role'=>2
                ];
                
                // insert en db
                if ($this->getDB()->insert('users',$data)){
                    header('Location:'.BASE);
                }
            
            }
        }else{
            header('Location:'.BASE.'user/register');
        }
    }

    private function auth($email, $pass)
    {
        try {
            $db = $this->getDB();
            $stmt = $db->prepare('SELECT * FROM users WHERE email=:email LIMIT 1');
            $stmt->execute([':email' => $email]);
            $count = $stmt->rowCount();
            $row = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if ($count == 1) {
                $user = $row[0];
                $res = password_verify($pass, $user['passw']);

                if ($res) {
                    return $user;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            return false;
        }
    }
}
