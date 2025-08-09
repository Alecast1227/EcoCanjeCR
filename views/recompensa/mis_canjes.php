<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php if(session_status()===PHP_SESSION_NONE){ session_start(); } ?>
<div class="container my-4">
  <h2 class="fw-bold text-success mb-3">Mis canjes</h2>

  <div class="d-flex gap-2 mb-3">
    <a href="index.php?controller=Recompensa&action=index" class="btn btn-outline-success">Volver a recompensas</a>
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
            <th>#</th>
            <th>Fecha</th>
            <th>Recompensa</th>
            <th>Cantidad</th>
            <th>Puntos usados</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($canjes && $canjes->num_rows): ?>
            <?php while($row = $canjes->fetch_assoc()): ?>
              <tr>
                <td><?php echo (int)$row['ID_Canje']; ?></td>
                <td><?php echo htmlspecialchars($row['Fecha_Canje']); ?></td>
                <td><?php echo htmlspecialchars($row['Recompensa']); ?></td>
                <td><?php echo (int)$row['Cantidad']; ?></td>
                <td><?php echo (int)$row['Puntos_Usados']; ?></td>
                <td>
                  <a class="btn btn-sm btn-success" href="index.php?controller=Recompensa&action=detalle&id=<?php echo $row['ID_Canje']; ?>">Ver</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center text-muted py-4">Aún no has realizado canjes.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>