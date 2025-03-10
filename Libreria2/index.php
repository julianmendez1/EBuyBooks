<?php
require_once 'config/database.php';
require_once 'app/controllers/LibroController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'index';

$db = (new Database())->getConnection();
$libroController = new LibroController($db);

switch ($action) {
    case 'comprar':
        $libroController->comprar();
        break;
    case 'agregar':
        $libroController->agregar();
        break;
    case 'guardar_libro':
        $libroController->guardarLibro();
        break;
    case 'agregar_al_carrito':
        $libroController->agregarAlCarrito();
        break;
    case 'ver_carrito':
        $libroController->verCarrito();
        break;
    case 'eliminar_del_carrito':
        $libroController->eliminarDelCarrito();
        break;
    case 'buscar_ajax':
        $libroController->buscarAjax();
        break;
    default:
        $libroController->index();
        break;
}