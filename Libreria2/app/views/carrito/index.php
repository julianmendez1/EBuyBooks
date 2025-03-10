<link rel="stylesheet" href="/libreria2/assets/css/styles.css">
<br>
<div class="container my-5">
    <h1 class="text-center mb-4">Carrito de Compras</h1>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success text-center"><?php echo $_SESSION['mensaje']; ?></div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <?php if (empty($carrito)): ?>
        <div class="alert alert-info text-center">Tu carrito está vacío.</div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-8">
                <?php foreach ($carrito as $item): ?>
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="<?php echo $item['img']; ?>" class="img-fluid rounded-start" alt="<?php echo $item['titulo']; ?>">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $item['titulo']; ?></h5>
                                    <p class="card-text"><?php echo $item['autor']; ?></p>
                                    <p class="card-text">$<?php echo $item['precio']; ?></p>
                                    <p class="card-text">Cantidad: <?php echo $item['cantidad']; ?></p>
                                    <a href="index.php?action=eliminar_del_carrito&id=<?php echo $item['id']; ?>" class="btn btn-danger">Eliminar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Resumen del Carrito</h5>
                        <?php
                        $total = 0;
                        foreach ($carrito as $item) {
                            $total += $item['precio'] * $item['cantidad'];
                        }
                        ?>
                        <p class="card-text">Total: $<?php echo number_format($total, 2); ?></p>
                        <a href="index.php?action=comprar" class="btn btn-success">Comprar</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="text-center">
    <div class="btn btn-success">
        <h2><a href="/libreria2/index.php" class="enlace-blanco">Volver</a></h2>
    </div>
</div>
</div>