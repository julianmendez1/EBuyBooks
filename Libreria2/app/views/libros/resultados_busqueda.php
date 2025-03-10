<?php if (empty($libros)): ?>
    <div class="col-12">
        <div class="alert alert-warning text-center">No se encontraron libros que coincidan con la búsqueda.</div>
    </div>
<?php else: ?>
    <?php foreach ($libros as $libro): ?>
        <div class="col">
            <div class="card h-100 shadow">
                <img src="<?php echo $libro['img']; ?>" width="300px" height="550px" class="card-img-top" alt="<?php echo $libro['titulo']; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $libro['titulo']; ?></h5>
                    <p class="card-text text-muted"><?php echo $libro['autor']; ?></p>
                    <p class="card-text">$<?php echo $libro['precio']; ?></p>
                    <p class="card-text"><small class="text-muted">Stock: <?php echo $libro['stock']; ?></small></p>
                    <form action="index.php?action=agregar_al_carrito" method="POST">
                        <input type="hidden" name="id_libro" value="<?php echo $libro['id']; ?>">
                        <div class="d-flex justify-content-between align-items-center">
                            <input type="number" name="cantidad" class="form-control w-50" min="1" max="<?php echo $libro['stock']; ?>" value="1">
                            <button type="submit" class="btn btn-primary">Agregar al Carrito</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>