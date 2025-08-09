<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<?php if(session_status()===PHP_SESSION_NONE){ session_start(); } ?>
<div class="container my-4">
  <h2 class="fw-bold text-success mb-3">Lista de recompensas</h2>

  <div class="d-flex gap-2 mb-3">
    <a href="index.php" class="btn btn-success">Inicio</a>
    <a href="index.php?controller=Recompensa&action=misCanjes" class="btn btn-outline-success">Mis canjes</a>
  </div>

  <?php if (!empty($_SESSION['flash_ok'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['flash_ok']; unset($_SESSION['flash_ok']); ?></div>
  <?php endif; ?>
  <?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger"><?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-success">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Puntos</th>
            <th>Stock</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php while($row = $recompensas->fetch_assoc()): ?>
          <tr>
            <td><?php echo (int)$row['ID_Recompensa']; ?></td>
            <td><?php echo htmlspecialchars($row['Nombre']); ?></td>
            <td><?php echo (int)$row['Puntos_Requeridos']; ?></td>
            <td><?php echo (int)$row['Cantidad_Disponible']; ?></td>
            <td>
              <?php if ((int)$row['Cantidad_Disponible'] > 0): ?>
                <a class="btn btn-success btn-sm" href="index.php?controller=Recompensa&action=canjear&id=<?php echo $row['ID_Recompensa']; ?>">Canjear</a>
              <?php else: ?>
                <span class="badge text-bg-secondary">Sin stock</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>