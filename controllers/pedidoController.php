<?php
// Incluir los modelos necesarios para interactuar con la base de datos
include_once "models/pedidos/PedidoDAO.php";
include_once "models/cesta/CestaDAO.php";

class PedidoController {
    
    // Método para mostrar la cesta de compra del usuario
    public function comprar() {
        // Obtener la cesta del usuario actual utilizando su ID de sesión
        $cesta = CestaDAO::getCesta($_SESSION['user_id']);
        
        // Incluir la vista 'comprar.php' para mostrar la cesta al usuario
        include_once "views/comprar.php";
    }

    // Método para mostrar los pedidos realizados por el usuario
    public function misPedidos() {
        // Obtener los pedidos del usuario utilizando su ID de sesión
        $pedidosUser = PedidoDAO::getPedidos($_SESSION['user_id']);
        
        // Incluir la vista 'mis-pedidos.php' para mostrar los pedidos del usuario
        include_once 'views/mis-pedidos.php';
    }

    // Método para mostrar los detalles de un pedido individual
    public function pedidoInidividual() {
        // Obtener los detalles de un pedido específico usando el ID de pedido desde la URL
        $pedidoIndividual = PedidoDAO::getPedidoIndividual($_GET['id_pedido']);
        
        // Incluir la vista 'pedido-individual.php' para mostrar los detalles del pedido
        include_once "views/pedido-individual.php";
    }

    // Método para crear un nuevo pedido
    public function crearPedido() {
        // Obtener los datos del usuario desde la sesión y la información del formulario
        $idUser = $_SESSION['user_id'];
        $direccion = $_POST['deliveryOption'];  // Dirección de entrega seleccionada
        $metodoPago = $_POST['PayOption'];     // Método de pago seleccionado
        $id_oferta = $_POST['id_oferta'];      // ID de la oferta seleccionada

        // Llamar al método para insertar un nuevo pedido en la base de datos
        $resultado = PedidoDAO::insertarPedido($idUser, $direccion, $metodoPago, $id_oferta);

        // Redirigir al usuario a la página principal después de crear el pedido
        header ("Location: ?url=index");
    }
    
}
?>
