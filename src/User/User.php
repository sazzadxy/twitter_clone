<?php 
namespace TwitterClone\User;
use PDO;
use TwitterClone\Database\Database;

class User extends Database
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function checkInput($var)
    {
        #$var = htmlentities($var, ENT_QUOTES);
        $var = trim($var);
        $var = stripcslashes($var);
        return $var;
    }

    public static function preventAccess($request, $currentFile, $currently)
    {
        if ($request == "GET" && $currentFile == $currently)  {
	        self::redirect('index.php');
        }
    }

    public static function redirect($location)
    {
        header("Location:".BASE_URL. $location);
    }

    public function register($email, $screenName, $password)
    {
        $stmt = $this->db->prepare("INSERT INTO users (email, screenName, password, profileImage, profileCover, username, following, followers, bio, country, website) VALUES (:email, :screenName, :password, 'assets/images/defaultProfileimage.png', 'assets/images/defaultCoverImage.png', '', '0', '0', '', '', '')");
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":screenName", $screenName);
        $stmt->bindParam(":password", $password);
        $stmt->execute();

        $user_id =  $this->db->lastInsertId();
        $_SESSION['user_id'] = $user_id;

    }

    public function search($search)
    {
        $stmt = $this->db->prepare("SELECT user_id, username, screenName, profileImage, profileCover FROM users WHERE username LIKE :search OR screenName LIKE :search ");
        $stmt->bindValue(":search", '%'.$search.'%', PDO::PARAM_STR);
        $stmt->bindValue(":search", '%'.$search.'%', PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function create($table, $fields = array())
    {
        $columns = implode(',', array_keys($fields));
        $values = ':'.implode(', :', array_keys($fields));
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        if ($stmt = $this->db->prepare($sql)) {
            foreach ($fields as $key => $value) {
                $stmt->bindValue(':'.$key, $value);
            }
            $stmt->execute();
            return $this->db->lastInsertId();
        }
    }

    public function update($table, $user_id, $fields = array())
    {
        $columns = '';
        $i       = 1;
        foreach ($fields as $name => $value) {
            $columns .= "$name = :$name";
            if ($i < count($fields)) {
                $columns .= ', ';
            }
            $i++;
        }
        $sql = "UPDATE $table SET $columns WHERE user_id = $user_id";
        if($stmt = $this->db->prepare($sql)) {
            foreach ($fields as $key => $value) {
                $stmt->bindValue(':'.$key , $value);
            }
            //var_dump($sql);
            $stmt->execute();
        }
    }

    public function delete($table, $array)
    {
        $sql   = "DELETE FROM `{$table}`";
        $where = " WHERE ";

        foreach ($array as $name => $value) {
            $sql .= "{$where} `{$name}` = :{$name}";
            $where = " AND ";
        }

        if ($stmt = $this->db->prepare($sql)) {
            foreach ($array as $name => $value) {
                $stmt->bindValue(':'.$name, $value);
            }
            $stmt->execute();
        }
    }

    
    // public function login($email, $password)
    // {   
    //     $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
    //     $stmt->bindParam(":email", $email);
    //     $stmt->bindParam(":password", password_hash($password, PASSWORD_BCRYPT));
    //     $stmt->execute();

    //     $user = $stmt->fetch(PDO::FETCH_OBJ);
    //     $count = $stmt->rowCount();
    //     //var_dump($count);
    //     if ($count > 0) {
    //         $_SESSION['user_id'] = $user->user_id;
    //         $hash = $user->password;
            
    //         if (password_verify($password, $hash)) {
    //             self::redirect('home.php');
    //         } else {
    //             return false;
    //         }
    //     }
    // }

    public function userData($user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function get($table, $fields = array())
    {
        $columns = implode(', ', array_keys($fields));
        $sql = "SELECT * FROM `{$table}` WHERE `{$columns}` = :{$columns}";
        if ($stmt = $this->db->prepare($sql)) {
            foreach ($fields as $key => $value) {
                $stmt->bindValue(":{$key}", $value);
            }
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
    }

    public function emailExists($email)
    {
        $email = $this->get('users', array('email' => $email));
        return ((!empty($email))) ? $email : false;
    }

    public function checkEmail($email)
    {
        $stmt = $this->db->prepare("SELECT email FROM users WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        $count = $stmt->rowCount();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkUsername($username)
    {
        $stmt = $this->db->prepare("SELECT username FROM users WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        
        $count = $stmt->rowCount();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkPassword($password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("SELECT password FROM users WHERE password = :password");
        $stmt->bindParam(":password", $hash);
        $stmt->execute();
        
        $count = $stmt->rowCount();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function userIdbyUsername($username)
    {
        $stmt = $this->db->prepare("SELECT user_id FROM users WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_OBJ);
        return $user->user_id;
    }

    public function hash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function logout()
    {
        $_SESSION = array();
        session_destroy();
        self::redirect('index.php');
    }

    public function isLoggedIn()
    {
        return (isset($_SESSION['user_id'])) ? true : false ;
    }

}



?>