<?php
include_once "models/users/User.php";
include_once "models/users/UserDAO.php";

class UserController{
    public function login(){
        include_once "views/login.php";
    }

    public function registro(){
        include_once "views/registro.php";
    }

    public function datosAcceso(){
        include_once 'views/perfil/datos-acceso.php';
    }

    public function cambiarContra(){
        include_once 'views/perfil/datos-cambiar-contra.php';
    }

    public function cuenta(){
        include_once "views/cuenta.php";
    }

    public function datosPersonales(){
        include_once "views/perfil/datos-personales.php";
    }


    public function create(){
        $email = $_POST['email'];
        $contra = $_POST['contra'];
        $nombre = $_POST['nombre'];
        $apellidos = !empty($_POST['apellidos']) ? $_POST['apellidos'] : null;
        $telefono = !empty($_POST['telefono']) ? $_POST['telefono'] : null;
        $direction = !empty($_POST['direction']) ? $_POST['direction'] : null;

        $contraHashed = password_hash($contra, PASSWORD_DEFAULT);

        $user = new User();
        $user->setEmail_user($email);
        $user->setPassword_user($contraHashed);
        $user->setNombre_user($nombre);
        $user->setApellidos_user($apellidos);
        $user->setTelefono_user($telefono);
        $user->setDirection_user($direction);
        
        

        UserDAO::create($user);
        header("Location: ?url=login");
    }
    public function entrar() {        
        if (empty($_POST['login-email']) || empty($_POST['login-contra'])) {
            echo "Por favor completa todos los campos.";
            return;
        }
    
        $loginEmail = $_POST['login-email'];
        $loginContra = $_POST['login-contra'];
    
        echo "Email: $loginEmail<br>";
        echo "Contraseña: $loginContra<br>";
    
        
        $users = UserDAO::getAllUsers();
        foreach ($users as $user) {
            echo "Email esperado: " . $user->getEmail_user() . "<br>";
            echo "Contraseña almacenada: " . $user->getPassword_user() . "<br>";
            
            if ($user->getEmail_user() === $loginEmail) {
                echo "El email coincide.<br>";
                
                
                if (password_verify($loginContra, $user->getPassword_user())) {
                    echo "La contraseña también coincide.<br>";
                    
                    session_start();
                    $_SESSION['user_id'] = $user->getId_user();
                    $_SESSION['user_name'] = $user->getNombre_user();
                    $_SESSION['user_email'] = $user->getEmail_user();
                    $_SESSION['user_apellidos'] = $user->getApellidos_user();
                    $_SESSION['user_direction'] = $user->getDirection_user();
                    $_SESSION['user_telefono'] = $user->getTelefono_user();
                    $_SESSION['user_rol'] = $user->getAdmin_rol();
                    header("Location: ?url=index");
                    exit;
                } else {
                    echo "La contraseña no coincide.<br>";
                }
            }
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: ?url=index");
        exit;
    }

    public function actualizarPerfil() {                
        $id = $_SESSION['user_id']; 
        $nombre = $_POST['nombre-actualizar'];
        $apellidos = !empty($_POST['apellidos-actualizar']) ? $_POST['apellidos-actualizar'] : null;
        $direccion = !empty($_POST['direction-actualizar']) ? $_POST['direction-actualizar'] : null;
        $telefono = !empty($_POST['telefono-actualizar']) ? $_POST['telefono-actualizar'] : null;
        
        $resultado = UserDAO::actualizarPerfil($id, $nombre, $apellidos, $direccion, $telefono);
        
        if ($resultado) {
            $_SESSION['user_name'] = $nombre;
            $_SESSION['user_apellidos'] = $apellidos;
            $_SESSION['user_direction'] = $direccion;
            $_SESSION['user_telefono'] = $telefono;
        
            header("Location: ?url=datos-personales");
            exit;
        } else {
            echo "Hubo un error al actualizar el perfil. Inténtalo de nuevo.";
        }
    }
    
    public function updateContra() {

        if (empty($_POST['actu-contra']) || empty($_POST['confirm-contra'])) {
            echo "Por favor completa todos los campos.";
            return;
        }

        $nuevaContrasena = $_POST['actu-contra'];
        $confirmContrasena = $_POST['confirm-contra'];

        $contraHashed = password_hash($confirmContrasena, PASSWORD_DEFAULT);
        if (strlen($nuevaContrasena) < 8) {
            echo "La contraseña debe tener al menos 8 caracteres.";
            return;
        }

        if ($nuevaContrasena !== $confirmContrasena) {
            echo "Las contraseñas no coinciden.";
            return;
        }

        session_start();
        $idUsuario = $_SESSION['user_id'];
    
        $resultado = UserDAO::updateContra($idUsuario, $contraHashed);
    
        if ($resultado) {
            echo "Contraseña actualizada correctamente.";
            header("Location: ?url=datos-acceso");
            exit;
        } else {
            echo "Hubo un error al actualizar la contraseña. Inténtalo de nuevo.";
        }
    }      
    
}   
?>