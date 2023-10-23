<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>AppStock - Inventario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../public/js/inventario.js"></script>


</head>

<style>
    /* Estilos de Flexbox */
    .container-fluid {
        display: flex;
        flex-wrap: wrap;
    }

    .sidebar {
        flex: 0 0 200px;
    }

    .main-content {
        flex: 1;
        padding: 20px;
    }

    .row.flex-lg-nowrap {
        flex-wrap: nowrap;
    }

    .flex-column {
        flex: 1;
        margin-right: 20px;
        margin-bottom: 20px;
    }
</style>

<?php
require_once('../../models/inventario.php');
require_once('../../models/entrada.php');
require_once('../../models/salida.php');
$inventarioList = json_decode(Inventario::getAllByJson(), true);
?>

<body>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <div class="container-fluid">
        <div class="row flex-lg-nowrap">
            <!--MENU-->
            <?php include $_SERVER['DOCUMENT_ROOT'].'/AbarrotesSTEP/views/partial/menu.php'?>    

            <div class="main-content col">
                <div class="e-tabs mb-3 px-3">
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link active" href="#">AppStock</a></li>
                    </ul>
                </div>
                <div class="row flex-lg-nowrap">
                    <div class="flex-column col mb-3">
                        <div class="e-panel card">
                            <div class="card-body">
                                <div class="card-title">
                                    <h6 class="mr-2"><span>Inventario</span></h6>
                                </div>
                                <div class="e-table">
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="align-top">
                                                        <div class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0">
                                                            <input type="checkbox" class="custom-control-input" id="all-items">
                                                            <label class="custom-control-label" for="all-items"></label>
                                                        </div>
                                                    </th>

                                                    <th scope="col">Almacen</th>
                                                    <th scope="col">Producto ID</th>
                                                    <th scope="col">Categoría</th>
                                                    <th scope="col">Subcategoría</th>
                                                    <th scope="col">Nombre</th>
                                                    <th scope="col">Descripción</th>
                                                    <th scope="col">Foto</th>
                                                    <th scope="col">Cantidad</th>
                                                    <th scope="col">Nivel</th>

                                                </tr>
                                            </thead>
                                            <tbody id="inventario-table-body">
                                                <?php foreach ($inventarioList as $inventario) {?>
                                                <tr>
                                                    <td class='align-top'>
                                                        <div class='custom-control custom-control-inline custom-checkbox custom-control-nameless m-0'>
                                                            <input type='checkbox' class='custom-control-input' id='item-" . $inventario->getInventario_id() . "'>
                                                            <label class='custom-control-label' for='item-" . $inventario->getInventario_id() . "'></label>
                                                        </div>
                                                    </td>
                                                    <td scope='col'><?php $inventario['almacen_id']['nombre']?> </td>
                                                    <td scope='col'><?php $inventario['producto_id']['producto_id']?></td>
                                                    <td scope='col'><?php $inventario['producto_id']['categoria_id']['nombre']?></td>
                                                    <td scope='col'><?php $inventario['producto_id']['subcategoria_id']['nombre'] ?></td>
                                                    <td scope='col'><?php $inventario['producto_id']['nombre'] ?></td>
                                                    <td scope='col'><?php $inventario['producto_id']['descripcion'] ?></td>
                                                    <td scope='col'><?php $inventario['producto_id']['foto']?></td>
                                                    <td scope='col'><?php $inventario['cantidad']?></td>
                                                    <td scope='col'>Nivel</td>";
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Paginación -->
                                    <div class="d-flex justify-content-center">
                                        <ul class="pagination mt-3 mb-0">
                                            <li class="disabled page-item"><a href="#" class="page-link">‹</a></li>
                                            <li class="active page-item"><a href="#" class="page-link">1</a></li>
                                            <li class="page-item"><a href="#" class="page-link">2</a></li>
                                            <li class="page-item"><a href="#" class="page-link">3</a></li>
                                            <li class="page-item"><a href="#" class="page-link">4</a></li>
                                            <li class="page-item"><a href="#" class="page-link">5</a></li>
                                            <li class="page-item"><a href="#" class="page-link">›</a></li>
                                            <li class="page-item"><a href="#" class="page-link">»</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-column col-12 col-lg-3 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center px-xl-3">
                                    <button class="btn btn-success btn-block" type="button" data-toggle="modal" data-target="#entrada-form-modal">Agregar Entrada</button>
                                    <button class="btn btn-danger btn-block" type="button" data-toggle="modal" data-target="#salida-form-modal">Agregar Salida</button>
                                </div>
                                <hr class="my-3">
                                <div class="e-navlist e-navlist--active-bold">
                                    <ul class="nav">
                                        <li class="nav-item active"><a href class="nav-link"><span>Mostrar Todos</span>&nbsp;<small>/&nbsp;32</small></a></li>
                                        <li class="nav-item"><a href class="nav-link"><span>Activos</span>&nbsp;<small>/&nbsp;16</small></a></li>
                                        <li class="nav-item"><a href class="nav-link"><span>Seleccionados</span>&nbsp;<small>/&nbsp;0</small></a></li>
                                    </ul>
                                </div>
                                <hr class="my-3">
                                <div>
                                    <div class="form-group">
                                        <label>Fecha de - a:</label>
                                        <div>
                                            <input id="dates-range" class="form-control flatpickr-input" placeholder="01 Dec 17 - 27 Jan 18" type="text" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Busqueda por nombre:</label>
                                        <div><input class="form-control w-100" type="text" placeholder="Name" value></div>
                                    </div>
                                </div>
                                <hr class="my-3">
                                <div>
                                    <label>Estatus:</label>
                                    <div class="px-2">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" name="user-status" id="users-status-disabled">
                                            <label class="custom-control-label" for="users-status-disabled">Inactivo</label>
                                        </div>
                                    </div>
                                    <div class="px-2">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" name="user-status" id="users-status-active">
                                            <label class="custom-control-label" for="users-status-active">Activo</label>
                                        </div>
                                    </div>
                                    <div class="px-2">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" name="user-status" id="users-status-any" checked>
                                            <label class="custom-control-label" for="users-status-any">Todos</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para Agregar Entrada -->
                <div class="modal fade" id="entrada-form-modal" tabindex="-1" role="dialog" aria-labelledby="entrada-form-modal-label" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="entrada-form-modal-label">Agregar Entrada</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="entrada-form" method="POST" action="./controllers/entradaController.php">
                                    <!-- Campos para agregar entrada -->
                                    <input type="hidden" id="inventario-id" name="inventario-id" value="">
                                    <div class="form-group">
                                        <label for="cantidad-entrada">Cantidad de Entrada</label>
                                        <input type="number" class="form-control" id="cantidad" name="cantidad">
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha-entrada">Fecha de Entrada</label>
                                        <input type="date" class="form-control" id="fecha" name="fecha">
                                    </div>

                                    <button type="submit" name="entrada_id" id="guardar-entrada-button" class="btn btn-success">Guardar Entrada</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para Agregar Salida -->
                <div class="modal fade" id="salida-form-modal" tabindex="-1" role="dialog" aria-labelledby="salida-form-modal-label" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="salida-form-modal-label">Agregar Salida</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="salida-form" method="POST" action="./controllers/salidaController.php">
                                    <!-- Campos para agregar salida -->
                                    <div class="form-group">
                                        <label for="fecha-salida">Fecha de Salida</label>
                                        <input type="date" class="form-control" id="fecha" name="fecha">
                                    </div>
                                    <div class="form-group">
                                        <label for="cantidad-salida">Cantidad de Salida</label>
                                        <input type="number" class="form-control" id="cantidad" name="cantidad">
                                    </div>
                                    <input type="hidden" id="inventario-id" name="inventario-id" value="<?php echo $inventario->getInventario_id(); ?>">
                                    <button type="submit" name="salida_id" class="btn btn-success">Guardar Salida</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>