<br>
<div class="text-center mb-5">
    <h1 class="display-4">Nuestros Libros</h1>
    <p class="lead">Explora nuestra colección de libros.</p>

    <!-- Barra de búsqueda -->
    <form id="form-busqueda" class="mb-4">
        <div class="input-group">
            <input type="text" id="busqueda" class="form-control" placeholder="Buscar por título o autor..." required>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
    </form>
</div>

<!-- Contenedor para mostrar los resultados de la búsqueda -->
<div id="resultados-busqueda" class="row row-cols-1 row-cols-md-3 g-4">
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
</div>

<!-- Script para manejar la búsqueda con AJAX -->
<script>
document.getElementById('form-busqueda').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

    const busqueda = document.getElementById('busqueda').value; // Obtiene el valor de la búsqueda

    // Realiza la solicitud AJAX
    fetch(`index.php?action=buscar_ajax&q=${busqueda}`)
        .then(response => response.text()) // Convierte la respuesta a texto
        .then(data => {
            document.getElementById('resultados-busqueda').innerHTML = data; // Actualiza el contenido de los resultados
        })
        .catch(error => console.error('Error:', error)); // Maneja errores
});
</script>

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