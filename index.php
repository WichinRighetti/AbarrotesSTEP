<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>AppStock</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/productos.js"></script>
    <script src="js/eliminar.js"></script>
    <script src="js/selector.js"></script>

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
    require_once('models/Producto.php');
    $productos = Producto::getAllByJson();

    ?>

<body>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <div class="container-fluid">
        <div class="row flex-lg-nowrap">
            <div class="sidebar col-12 col-lg-auto mb-3">
                <div class="card p-3">
                    <div class="e-navlist e-navlist--active-bg">
                        <ul class="nav">
                            <li class="nav-item"><a class="nav-link px-2 active" href="index.php"><i class="fa fa-fw fa-bar-chart mr-1"></i><span>Almacen</span></a></li>
                            <li class="nav-item"><a class="nav-link px-2" href="infoInventario.php"><i class="fa fa-fw fa-th mr-1"></i><span>Inventario</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
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
                                    <h6 class="mr-2"><span>Productos</span></h6>
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

                                                    <th scope="col">Producto ID</th>
                                                    <th scope="col">Categoría</th>
                                                    <th scope="col">Subcategoría</th>
                                                    <th scope="col">Nombre</th>
                                                    <th scope="col">Descripción</th>
                                                    <th scope="col">Foto</th>
                                                    <th scope="col">Estatus</th>
                                                    <th scope="col"></th>
                                                    <th scope="col">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="productos-table-body">
                                                <!-- Aquí puedes llenar la tabla con los datos de los productos -->
                                                <?php foreach ($productos as $producto) : ?>
                                                    <tr>
                                                        <td class="align-middle">
                                                            <div class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0 align-top">
                                                                <input type="checkbox" class="custom-control-input" id="item-<?php echo $producto['producto_id']; ?>">
                                                                <label class="custom-control-label" for="item-<?php echo $producto['producto_id']; ?>"></label>
                                                            </div>
                                                        </td>
                                                        <td><?php echo $producto['producto_id']; ?></td>
                                                        <td><?php echo $producto['categoria_id']['nombre']; ?></td>
                                                        <td><?php echo $producto['subcategoria_id']['nombre']; ?></td>
                                                        <td><?php echo $producto['nombre']; ?></td>
                                                        <td><?php echo $producto['descripcion']; ?></td>
                                                        <td><?php echo $producto['foto']; ?></td>
                                                        <td><?php echo $producto['estatus']; ?></td>
                                                        <td></td>
                                                        <td>
                                                            <div class="btn-group align-top">
                                                                <button class="btn btn-sm btn-outline-secondary" type="button" data-toggle="modal" data-target="#user-form-modal">Edit</button>
                                                                <button class="btn btn-sm btn-outline-secondary deactivate-product" type="button" data-id="<?php echo $producto['producto_id']; ?>"><i class="fa fa-trash"></i></button>

                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
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
                                    <button class="btn btn-success btn-block" type="button" data-toggle="modal" data-target="#user-form-modal">Altas</button>
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
                <div class="modal fade" role="dialog" tabindex="-1" id="user-form-modal">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Create User</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="py-1">
                                    <form class="form" novalidate>
                                        <div class="row">
                                            <div class="col">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>Full Name</label>
                                                            <input class="form-control" type="text" name="name" placeholder="John Smith" value="John Smith">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>Username</label>
                                                            <input class="form-control" type="text" name="username" placeholder="johnny.s" value="johnny.s">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input class="form-control" type="text" placeholder="user@example.com">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <div class="form-group">
                                                            <label>About</label>
                                                            <textarea class="form-control" rows="5" placeholder="My Bio"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-sm-6 mb-3">
                                                <div class="mb-2"><b>Change Password</b></div>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>Current Password</label>
                                                            <input class="form-control" type="password" placeholder="••••••">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>New Password</label>
                                                            <input class="form-control" type="password" placeholder="••••••">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>Confirm <span class="d-none d-xl-inline">Password</span></label>
                                                            <input class="form-control" type="password" placeholder="••••••">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-5 offset-sm-1 mb-3">
                                                <div class="mb-2"><b>Keeping in Touch</b></div>
                                                <div class="row">
                                                    <div class="col">
                                                        z <label>Email Notifications</label>
                                                        <div class="custom-controls-stacked px-2">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="notifications-blog" checked>
                                                                <label class="custom-control-label" for="notifications-blog">Blog posts</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="notifications-news" checked>
                                                                <label class="custom-control-label" for="notifications-news">Newsletter</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="notifications-offers" checked>
                                                                <label class="custom-control-label" for="notifications-offers">Personal Offers</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col d-flex justify-content-end">
                                                <button class="btn btn-primary" type="submit">Save Changes</button>
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