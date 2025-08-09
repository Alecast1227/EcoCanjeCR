<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="container my-4">
  <h2 class="fw-bold text-success mb-3">Detalle de canje #<?php echo (int)$detalle['ID_Canje']; ?></h2>

  <div class="d-flex gap-2 mb-3">
    <a href="index.php?controller=Recompensa&action=misCanjes" class="btn btn-outline-success">Volver</a>
    <a href="index.php?controller=Recompensa&action=index" class="btn btn-success">Ver recompensas</a>
  </div>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-borderless mb-0">
        <tbody>
          <tr><th class="w-25">Fecha</th><td><?php echo htmlspecialchars($detalle['Fecha_Canje']); ?></td></tr>
          <tr><th>Recompensa</th><td><?php echo htmlspecialchars($detalle['Recompensa']); ?></td></tr>
          <tr><th>Descripción</th><td><?php echo nl2br(htmlspecialchars($detalle['Descripcion'] ?? '')); ?></td></tr>
          <tr><th>Cantidad</th><td><?php echo (int)$detalle['Cantidad']; ?></td></tr>
          <tr><th>Puntos por unidad</th><td><?php echo (int)$detalle['Puntos_Requeridos']; ?></td></tr>
          <tr><th>Puntos usados</th><td><?php echo (int)$detalle['Puntos_Usados']; ?></td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
