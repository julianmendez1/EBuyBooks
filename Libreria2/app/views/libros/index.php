<?php if (empty($libros)): ?>
    <div class="alert alert-warning text-center">No se encontraron libros que coincidan con la búsqueda.</div>
<?php endif; ?>

<div class="text-center mb-5">
    <h1 class="display-4">Nuestros Libros</h1>
    <p class="lead">Explora nuestra colección de libros.</p>

    <!-- Barra de búsqueda -->
    <form action="index.php?action=buscar" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" class="form-control" name="q" placeholder="Buscar por título o autor..." required>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
    </form>
</div>

<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($libros as $libro): ?>
        <div class="col">
            <div class="card h-100 shadow">
                <img src="<?php echo $libro['img']; ?>" width="300px" height="550px" class="card-img-top" alt="<?php echo $libro['titulo']; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $libro['titulo']; ?></h5>
                    <p class="card-text text-muted"><?php echo $libro['autor']; ?></p>
                    <p class="card-text">$<?php echo $libro['precio']; ?></p>
                    <p class="card-text"><small class="text-muted">Stock: <?php echo $libro['stock']; ?></small></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <input type="number" id="cantidad-<?php echo $libro['id']; ?>" class="form-control w-50" min="1" max="<?php echo $libro['stock']; ?>" value="1">
                        <button class="btn btn-primary" onclick="comprarLibro(<?php echo $libro['id']; ?>)">Comprar</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<script>
function comprarLibro(id) {
    const cantidad = document.getElementById(`cantidad-${id}`).value;
    fetch('index.php?action=comprar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id, cantidad: cantidad })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            window.location.reload();
        }
    });
}
</script>