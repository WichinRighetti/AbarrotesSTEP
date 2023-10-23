<div class="row">
    <div class="col">
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label>Categoria</label>
                    <select class="form-select form-select-lg mb-3" name="category">
                        <option selected>Open this select menu</option>
                        <?php foreach($categorias as $categoria){ ?>
                            <?php if($producto->getCategoriaId()->getCategoria_Id() == $categoria->categoria_id) {?>
                            <option value="<?php echo $categoria->categoria_id?>" selected><?php echo $categoria->nombre?></option>
                            <?php } else {?>
                                <option value="<?php echo $categoria->categoria_id?>"><?php echo $categoria->nombre?></option>
                            <?php }?>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label>Subcategoria</label>
                    <select class="form-select form-select-lg mb-3" name="subcategory">
                        <option selected>Open this select menu</option>
                        <?php foreach($subcategorias as $subcategoria){ ?>
                            <?php if($producto->getSubcategoriaId()->getSubcategoria_Id() == $subcategoria->subcategoria_id) {?>
                            <option value="<?php echo $subcategoria->subcategoria_id?>" selected><?php echo $subcategoria->nombre?></option>
                            <?php } else { ?>
                                <option value="<?php echo $subcategoria->subcategoria_id?>"><?php echo $subcategoria->nombre?></option>
                            <?php }?>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <input class="form-control" name="id" type="hidden" value="<?= $producto->getProductoId() != 0 ? $producto->getProductoId() : 0 ?>">
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label>Nombre</label>
                    <input class="form-control" name="name" type="text" value="<?= $producto->getNombre() != 0 ? $producto->getNombre() : '' ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label>Descripcion</label>
                    <input class="form-control" name="description" type="text" value="<?= $producto->getDescripcion() != 0 ? $producto->getDescripcion() : '' ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label>Foto</label>
                    <input class="form-control" name="photo" type="text" value="<?= $producto->getFoto() != 0 ? $producto->getFoto() : '' ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="active">Activo</label>                    
                    <input id="active" name="status" type="radio" value="1" <?php echo $producto->getEstatus() == 1 ? 'checked' : ''?>>                    
                    <label for="inactive">Inactivo</label>      
                    <input id="inactive" name="status" type="radio" value="0" <?php echo $producto->getEstatus() == 0 ? 'checked' : ''?>>
                </div>
            </div>
        </div>
    </div>
</div>