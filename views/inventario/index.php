<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>AppStock</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../public/js/infoInventario.js"></script>
    <script src="../../public/js/inventario.js"></script>
</head>
<?php
require_once('../../models/inventario.php');
$inventariosTotales = json_decode(Inventario::getAllByJson(), true);
$elementosPorPagina = 20;
$totalProductos = count($inventariosTotales);
$paginas = $totalProductos / $elementosPorPagina;
$paginas = ceil($paginas);
if(!$_GET){
    header('Location:index.php?pagina=1');
}
if ($_GET['pagina']>$paginas){
    header('Location:index.php?pagina=1');
}
$iniciar = ($_GET['pagina'] - 1) * $elementosPorPagina;
$inventarios = json_decode(Inventario::getAllPaginaByJson($iniciar, $elementosPorPagina), true);

?>
<body onload="loadAlmacenes(), loadCategorias()">
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
                                                <?php foreach ($inventarios as $inventario) {?>
                                                <tr>
                                                    <td class='align-top'>
                                                        <div class='custom-control custom-control-inline custom-checkbox custom-control-nameless m-0'>
                                                            <input type='checkbox' class='custom-control-input'>
                                                            <label class='custom-control-label'></label>
                                                        </div>
                                                    </td>
                                                    <td scope='col'><?php echo $inventario['almacen']['nombre']?> </td>
                                                    <td scope='col'><?php echo $inventario['producto']['producto_id']?></td>
                                                    <td scope='col'><?php echo $inventario['producto']['categoria_id']['nombre']?></td>
                                                    <td scope='col'><?php echo $inventario['producto']['subcategoria_id']['nombre'] ?></td>
                                                    <td scope='col'><?php echo $inventario['producto']['nombre'] ?></td>
                                                    <td scope='col'><?php echo $inventario['producto']['descripcion'] ?></td>
                                                    <td scope='col'><?php echo $inventario['producto']['foto']?></td>
                                                    <td scope='col'><?php echo $inventario['cantidad']?></td>
                                                    <td scope='col'>
                                                        <?php 
                                                        $nivel = '';
                                                        $bgcolor = '';
                                                        $txcolor = '';
                                                        $porcentaje = $inventario['cantidad'] * $inventario['maximo'] / 100;
                                                        if ($porcentaje > 0.30){
                                                            $nivel = 'Disponible';
                                                            $bgcolor = 'bg-success';
                                                            $txcolor = 'text-white';
                                                        } else if ($porcentaje > 0 && $porcentaje <= 0.30){
                                                            $nivel = "Proximo a estar fuera de stock";
                                                            $bgcolor = "bg-warning";
                                                            $txcolor = 'text-dark';
                                                        } else if($porcentaje <= 0){
                                                            $nivel = 'Fuera de Stock';
                                                            $bgcolor = 'bg-danger';
                                                            $txcolor = 'text-white';
                                                        }?>
                                                        <span class="badge <?php echo $bgcolor?> <?php echo $txcolor?>"><?php echo $nivel?></span>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Paginación -->
                                    <div class="d-flex justify-content-center">
                                        <ul class="pagination mt-3 mb-0">
                                        <li class="page-item <?php echo $_GET['pagina'] == 1 ? 'disabled' : ''?>">
                                                <a href="<?php echo 'index.php?pagina='.$_GET['pagina']-1?>" class="page-link">‹</a>
                                            </li>
                                            <?php for($i = 0; $i < $paginas; $i++) { ?>
                                            <li class="page-item <?php echo $_GET['pagina'] == $i+1 ? 'active' : ''?>">
                                                <a href="index.php?pagina=<?php echo ($i + 1)?>" class="page-link"><?php echo ($i + 1)?></a>
                                            </li>
                                            <?php } ?>
                                            <li class="page-item <?php echo $_GET['pagina'] == $paginas ? 'disabled' : ''?>">
                                                <a href="<?php echo 'index.php?pagina='.$_GET['pagina']+1?>" class="page-link">›</a>
                                            </li>
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
                                    <button class="btn btn-success btn-block" data-target="#entrada-form-modal" data-toggle="modal" type="button" onclick="getDataForm();">Agregar Entrada</button>
                                    <button class="btn btn-danger  btn-block" data-target="#salida-form-modal" data-toggle="modal" type="button" onclick="getDataForm();" >Agregar Salida</button>
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
                                        <label>Selecciona el almacen:</label>
                                        <div><select class="form-control w-100" id="slcAlmacen" onchange="getStockByFilter()"></select></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="form-group">
                                        <label>Busqueda por categoria:</label>
                                        <div><select class="form-control w-100" id="slcCategoria" onchange="getStockByFilter()"></select></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="form-group">
                                        <label>Busqueda por nombre:</label>
                                        <div><input class="form-control w-100" type="text" placeholder="Name" id="txtNombre" onkeydown="getStockByFilter();"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               <!-- Modal para Agregar Entrada -->
               <div aria-hidden="true" class="modal fade" id="entrada-form-modal" role="dialog" tabindex="-1">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Agregar Entrada</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="entrada-form" method="POST" action="../../controllers/inventarioController.php">
                                    <!-- Campos para agregar entrada -->
                                    <input type="hidden" id="inventario-id" name="inventario-id" value="">
                                    <input type="hidden" id="tipo" name="tipo" value="E">
                                    <div class="form-group">
                                        <label for="almacen-entrada">Almacen de Entrada</label>
                                        <select class="form-control" id="almacen-entrada" name="almacen_id">
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="producto-entrada">Articulo de Entrada</label>
                                        <select class="form-control" id="producto-entrada" name="producto_id">
                                        </select>
                                    </div>
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
                <div aria-hidden="true" class="modal fade" id="salida-form-modal" role="dialog" tabindex="-1">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Agregar Salida</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="salida-form" method="POST" action="../../controllers/inventarioController.php">
                                    <!-- Campos para agregar salida -->
                                    <input type="hidden" id="inventario-id" name="inventario-id" value="">
                                    <input type="hidden" id="tipo" name="tipo" value="S">
                                    <div class="form-group">
                                        <label for="almacen-salida">Almacen de Salida</label>
                                        <select class="form-control" id="almacen-salida" name="almacen_id">
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="producto-salida">Articulo de Salida</label>
                                        <select class="form-control" id="producto-salida" name="producto_id">
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="cantidad-salida">Cantidad de Salida</label>
                                        <input type="number" class="form-control" id="cantidad" name="cantidad">
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha-salida">Fecha de Salida</label>
                                        <input type="date" class="form-control" id="fecha" name="fecha">
                                    </div>                                    
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