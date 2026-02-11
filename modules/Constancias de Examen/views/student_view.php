

<?php
// Add CSS
echo "<link rel='stylesheet' type='text/css' href='" . $session->get('absoluteURL') . "/modules/Constancias de Examen/studentView/css/student.css' />";
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">';
    
echo '<div class="constancias-module">';
echo '<div id="solicitudesTableContainer"></div>';
echo '<div id="solicitudMsg"></div>';
?>

<div style="display: flex; justify-content: flex-end; margin-bottom: 1.5em;">
    <button id="abrirModalConstancia" class="button button--primary">Solicitar constancia</button>
</div>

<!-- Modal -->
<div id="modalConstancia" class="modal-constancia" style="display:none;">
  <div class="modal-constancia-content">
    <span class="modal-constancia-close" id="cerrarModalConstancia">&times;</span>
    <h2>Solicitar constancia</h2>
    <form id="constanciaRequestForm" autocomplete="off">
      <!-- ... todo el formulario ... -->
    </form>
  </div>
</div>

<script>
// Todo el JavaScript de recargarTablaSolicitudes
// Todo el JavaScript del modal
// Todo el JavaScript del autocomplete
</script>

<?php echo '</div>'; ?>