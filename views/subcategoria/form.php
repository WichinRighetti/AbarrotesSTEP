<div class="row">
    <div class="col">
        <input class="form-control" name="id" type="hidden" value="<?= $subcategoria->getSubcategoria_id() != 0 ? $subcategoria->getSubCategoria_id() : 0 ?>">
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label>Nombre</label>
                    <input class="form-control" name="name" type="text" value="<?= $subcategoria->getNombre() == "" ? '' : $subcategoria->getNombre() ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="active">Activo</label>                    
                    <input id="active" name="status" type="radio" value="1" <?php echo $subcategoria->getEstatus() == 1 ? 'checked' : ''?>>                    
                    <label for="inactive">Inactivo</label>      
                    <input id="inactive" name="status" type="radio" value="0" <?php echo $subcategoria->getEstatus() == 0 ? 'checked' : ''?>>
                </div>
            </div>
        </div>
    </div>
</div>