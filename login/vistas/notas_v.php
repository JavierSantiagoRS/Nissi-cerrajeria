<?php

session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}



include '../conexion.php';
include '../modelos/notas_m.php';
$notas = obtenerNotas($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD Notas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="text-left m-3">
    <a href="../controlador/usuarios_c.php?accion=salir" class="btn btn-secondary">salir</a>
    <h2 class="text-center">bienvenido(a) <?php echo $_SESSION["nombre"] ?></h2>
</div>

    <div class="container mt-5">
        <h1 class="mb-4">Listado de Notas</h1>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCreate">Agregar Nota</button>
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Contenido</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($notas as $nota): ?>
            <tr>
                <td><?= $nota['id'] ?></td>
                <td><?= $nota['titulo'] ?></td>
                <td><?= $nota['contenido'] ?></td>
                <td>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $nota['id'] ?>">Editar</button>
                    <a href="../controlador/notas_c.php?accion=eliminar&id=<?= $nota['id'] ?>" class="btn btn-danger">Eliminar</a>
                </td>
            </tr>
            <!-- Modal Editar -->
            <div class="modal fade" id="modalEdit<?= $nota['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Nota</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="../controlador/notas_c.php?accion=actualizar" method="POST">
                                <input type="hidden" name="id" value="<?= $nota['id'] ?>">
                                <div class="mb-3">
                                    <label class="form-label">Título</label>
                                    <input type="text" name="titulo" class="form-control" value="<?= $nota['titulo'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Contenido</label>
                                    <textarea name="contenido" class="form-control"><?= $nota['contenido'] ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Modal Crear -->
    <div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Nota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../controlador/notas_c.php?accion=crear" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Título</label>
                            <input type="text" name="titulo" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contenido</label>
                            <textarea name="contenido" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Agregar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>