<?php
include_once "models/productos/ProductosDAO.php";
include_once "models/categorias/CategoriaProductoDAO.php";
include_once "models/cesta/CestaDAO.php";

class ProductoController{
    public function productos(){
        $productos = ProductosDAO::getAll();
        $categoriasProducto = CategoriaProductoDAO::getAllCategoriaProducto();
        $ofertas = CestaDAO::getAllCupones();
        $total_productos = ProductosDAO::countAll();
        include_once 'views/productos.php';
    }
    
    public function productoIndividual(){
        $id = intval($_GET['id']);
        $categoriaId = CategoriaProductoDAO::getCategoriaProductoById($id);
        $productoIndividual = ProductosDAO::getProductoIndividual($id);
        include_once 'views/producto-individual.php';        
    }

    public function productosByCategoria(){
        $id = intval($_GET['categoria']);
        $productos = ProductosDAO::getProductosByCategoria($id);
        $total_productos = ProductosDAO::countProductosByCategoria($id);
        $categoriasProducto = CategoriaProductoDAO::getAllCategoriaProducto();
        $categoriaId = CategoriaProductoDAO::getCategoriaProductoById($id);
        $ofertas = CestaDAO::getAllCupones();
        include_once 'views/productos.php';
    }

    public function productosByOferta(){
        $id = intval($_GET['oferta']);
        $productos = ProductosDAO::getProductosByOferta($id);
        include_once 'views/productos.php';
    }

}
?>
