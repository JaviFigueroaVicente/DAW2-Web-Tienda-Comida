<?php
include_once "config/dataBase.php";
include_once "models/users/User.php";

class UserDAO{
    public static function getAllUsers(){
        $con = DataBase::connect();
        $stmt = $con->prepare("SELECT * FROM users");

        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        while ($data = $result->fetch_assoc()) {
            $user = new User();
            $user->setId_user($data['id_user']);
            $user->setEmail_user($data['email']);
            $user->setPassword_user($data['contra']);
            $user->setNombre_user($data['nombre']);
            $user->setApellidos_user($data['apellidos'] ?? null);
            $user->setTelefono_user($data['telefono'] ?? null);
            $user->setDirection_user($data['direction'] ?? null);

            $users[] = $user; 
        }

        $con->close();
        return $users;
    }

    public static function create($user){
        $con = DataBase::connect();
        $stmt = $con->prepare("INSERT INTO users(email,contra,nombre,apellidos,telefono,direction) VALUES (?,?,?,?,?,?)");

        $email = $user->getEmail_user();
        $contra = $user->getPassword_user();
        $nombre = $user->getNombre_user();
        $apellidos = $user->getApellidos_user();
        $telefono = $user->getTelefono_user();
        $direction= $user->getDirection_user();
        
        $stmt->bind_param("ssssis", $email, $contra, $nombre, $apellidos, $telefono, $direction);

        $stmt->execute();
        $con->close();
    }
}
?>