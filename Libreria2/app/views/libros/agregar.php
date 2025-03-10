<br>
<div class="container my-5">
    <h1 class="text-center mb-4">Agregar Nuevo Libro</h1>
    <form action="index.php?action=guardar_libro" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="mb-3">
            <label for="autor" class="form-label">Autor</label>
            <input type="text" class="form-control" id="autor" name="autor" required>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock (Unidades)</label>
            <input type="number" class="form-control" id="stock" name="stock" required>
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Portada del Libro</label>
            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Subir Libro</button>
    </form>
</div>