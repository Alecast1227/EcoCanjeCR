<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="container my-4">
  <h2 class="fw-bold text-success mb-3">Canjear recompensa</h2>

  <div class="d-flex gap-2 mb-3">
    <a href="index.php?controller=Recompensa&action=index" class="btn btn-outline-success">Volver</a>
    <a href="index.php?controller=Recompensa&action=misCanjes" class="btn btn-success">Mis canjes</a>
  </div>

  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <p class="mb-1"><strong>Recompensa:</strong> <?php echo htmlspecialchars($recompensa['Nombre']); ?></p>
      <p class="mb-1"><strong>Puntos por unidad:</strong> <?php echo (int)$recompensa['Puntos_Requeridos']; ?></p>
      <p class="mb-1"><strong>Stock disponible:</strong> <?php echo (int)$recompensa['Cantidad_Disponible']; ?></p>
      <p class="mb-0"><strong>Mis puntos:</strong> <?php echo (int)$puntosUsuario; ?></p>
    </div>
  </div>

  <form method="POST" action="index.php?controller=Recompensa&action=storeCanje" onsubmit="return validarCantidad();" class="card shadow-sm p-3">
    <input type="hidden" name="id_recompensa" value="<?php echo $recompensa['ID_Recompensa']; ?>">
    <div class="mb-3">
      <label for="cantidad" class="form-label">Cantidad a canjear</label>
      <input type="number" id="cantidad" name="cantidad" class="form-control"
             min="1" max="<?php echo (int)$recompensa['Cantidad_Disponible']; ?>" value="1" required>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-success" type="submit">Confirmar canje</button>
      <a class="btn btn-outline-secondary" href="index.php?controller=Recompensa&action=index">Cancelar</a>
    </div>
  </form>
</div>

<script>
function validarCantidad(){
  const max = <?php echo (int)$recompensa['Cantidad_Disponible']; ?>;
  const req = <?php echo (int)$recompensa['Puntos_Requeridos']; ?>;
  const pts = <?php echo (int)$puntosUsuario; ?>;
  const cant = parseInt(document.getElementById('cantidad').value || "0", 10);
  if(cant < 1){ alert("La cantidad debe ser al menos 1."); return false; }
  if(cant > max){ alert("La cantidad no puede exceder el stock."); return false; }
  if(req * cant > pts){ alert("No tiene puntos suficientes para esta cantidad."); return false; }
  return true;
}
</script>
