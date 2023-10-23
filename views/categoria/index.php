<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>AppStock</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../public/js/categoria.js"></script>
</head>
<?php
    require_once('../../models/Categoria.php');
    $categoriasTotales = json_decode(Categoria::getAllByJson(), true);
    $elementosPorPagina = 20;
    $totalProductos = count($categoriasTotales);
    $paginas = $totalProductos / $elementosPorPagina;
    $paginas = ceil($paginas);
    if(!$_GET){
        header('Location:index.php?pagina=1');
    }
    if ($_GET['pagina']>$paginas){
        header('Location:index.php?pagina=1');
    }
    $iniciar = ($_GET['pagina'] - 1) * $elementosPorPagina;
    $categorias = json_decode(Categoria::getAllPaginaByJson($iniciar, $elementosPorPagina), true);
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
                                    <h6 class="mr-2"><span>Categorias</span></h6>
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

                                                    <th scope="col">Categoria ID</th>
                                                    <th scope="col">Nombre</th>
                                                    <th scope="col">Estatus</th>
                                                    <th scope="col">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="categorias-table-body">
                                                <!-- Aqu� puedes llenar la tabla con los datos de los categorias -->
                                                <?php foreach ($categorias as $categoria) : ?>
                                                    <tr>
                                                        <td class="align-middle">
                                                            <div class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0 align-top">
                                                                <input type="checkbox" class="custom-control-input" id="item-<?php echo $categoria['categoria_id']; ?>">
                                                                <label class="custom-control-label" for="item-<?php echo $categoria['categoria_id']; ?>"></label>
                                                            </div>
                                                        </td>
                                                        <td><?php echo $categoria['categoria_id']; ?></td>
                                                        <td><?php echo $categoria['nombre']; ?></td>
                                                        <td>
                                                            <?php if($categoria['estatus'] == 1){ ?>
                                                            <i class="fa fa-fw text-secondary cursor-pointer fa-toggle-on"/>
                                                            <?php } else{ ?>
                                                            <i class="fa fa-fw text-secondary cursor-pointer fa-toggle-off"/>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group align-top">
                                                                <button class="btn btn-sm btn-outline-secondary" type="button" onclick="getCategory(<?php echo $categoria['categoria_id']?>)" data-toggle="modal" data-target="#categoria-form-modal">Edit</button>
                                                                <button class="btn btn-sm btn-outline-secondary deactivate-product" type="button" onclick="deleteCategory(<?php echo $categoria['categoria_id']?>)"><i class="fa fa-trash"></i></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Paginaci�n -->
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
                                    <button class="btn btn-success btn-block" type="button" data-toggle="modal" onclick="cleanForm()" data-target="#categoria-form-modal">Altas</button>
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
                                        <label>Busqueda por nombre:</label>
                                        <div><input class="form-control w-100" type="text" placeholder="Name" id="txtNombre" onkeydown="getCategoriesByFilter()"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="categoria-form-modal" role="dialog" tabindex="-1">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Categoria</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">X</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="py-1">
                                    <form action="../../controllers/categoriaController.php" method="POST" class="form" novalidate>
                                        <div class="row">
                                            <div class="col">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input id="categoria_id" class="form-control" type="hidden" name="categoria_id" value="">
                                                            <label>Nombre</label>
                                                            <input id="nombre" class="form-control" type="text" name="nombre" placeholder="Demo" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col d-flex justify-content-end">
                                                <button class="btn btn-primary" type="submit">Guardar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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