<?php
use Gibbon\Forms\Form;
use Gibbon\Tables\DataTable;
use Gibbon\Services\Format;

// Module includes
require_once dirname(__DIR__) . '/moduleFunctions.php';

if (isActionAccessible($guid, $connection2, '/modules/Constancias de Examen/student_constancias.php') === false) {
    // Access denied
    $page->addError(__('You do not have access to this action.'));
} else {
    // Proceed!
    $page->breadcrumbs->add(__('Mis Constancias de Examen'));
    
    // Get current user info
    $gibbonPersonID = $session->get('gibbonPersonID');
    $userRole = null;
    if ($gibbonPersonID) {
        $data = array('gibbonPersonID' => $gibbonPersonID);
        $sql = "SELECT gibbonRole.name FROM gibbonPerson JOIN gibbonRole ON (gibbonPerson.gibbonRoleIDPrimary = gibbonRole.gibbonRoleID) WHERE gibbonPerson.gibbonPersonID = :gibbonPersonID LIMIT 1";
        $result = $connection2->prepare($sql);
        $result->execute($data);
        $row = $result->fetch();
        $userRole = $row ? $row['name'] : null;
    }

    if ($userRole !== 'Student') {
        $page->addError(__('Esta página es solo para estudiantes.'));
        return;
    }
    
    // Get student information directly using gibbonPersonID
    $data = array('gibbonPersonID' => $gibbonPersonID);
    $sql = "SELECT gibbonPersonID, username, firstName, surname, email, gibbonRoleIDPrimary 
            FROM gibbonPerson 
            WHERE gibbonPersonID = :gibbonPersonID AND status = 'Full' LIMIT 1";
    $result = $connection2->prepare($sql);
    $result->execute($data);
    
    if ($result->rowCount() != 1) {
        $page->addError(__('No se pudo obtener la información del estudiante.'));
        return;
    }
    
    $student = $result->fetch();
    $username = $student['username'];
    $firstName = $student['firstName'];
    $surname = $student['surname'];
    $email = $student['email'];
    
    // Get student DNI from gibbonPersonalDocument table
    // Paso 1: Obtener el ID del tipo de documento "Documento"
    $sqlTipo = "SELECT gibbonPersonalDocumentTypeID FROM gibbonPersonalDocumentType WHERE name = 'Documento'";
    $stmtTipo = $connection2->prepare($sqlTipo);
    $stmtTipo->execute();
    $tipoRow = $stmtTipo->fetch();

    if (!$tipoRow) {
        $page->addError(__('No se encontró el tipo de documento "Documento".'));
        return;
    }

    $tipoID = $tipoRow['gibbonPersonalDocumentTypeID'];

    // Paso 2: Buscar el documento del usuario
    $sqlDoc = "SELECT documentNumber FROM gibbonPersonalDocument 
               WHERE foreignTable = 'gibbonPerson' 
               AND foreignTableID = :gibbonPersonID 
               AND gibbonPersonalDocumentTypeID = :tipoID LIMIT 1";
    $stmtDoc = $connection2->prepare($sqlDoc);
    $stmtDoc->execute([
        'gibbonPersonID' => $gibbonPersonID,
        'tipoID' => $tipoID
    ]);

    if ($stmtDoc->rowCount() != 1) {
        $page->addError(__('No se encontró el documento del estudiante.'));
        return;
    }

    $rowDoc = $stmtDoc->fetch();
    $dniAlumno = $rowDoc['documentNumber'];
    
    // Add CSS
    echo "<link rel='stylesheet' type='text/css' href='" . $session->get('absoluteURL') . "/modules/Constancias de Examen/studentView/css/student.css' />";
    // Font Awesome for icons
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">';
        
    echo '<div class="constancias-module">'; // Wrapper div to match admin view
    echo '<div id="solicitudesTableContainer"></div>';
    echo '<div id="solicitudMsg"></div>';
    
    // Botón para abrir el modal, centrado encima de la tabla
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
          <div class="form-row">
            <label for="materia">Materia *</label>
            <div class="autocomplete-container">
              <input type="text" id="materia" name="materia" required maxlength="100" placeholder="Buscar materia...">
              <div id="materiaAutocomplete" class="autocomplete-dropdown"></div>
            </div>
          </div>
          <div class="form-row">
            <label for="fechaExamen">Fecha del Examen *</label>
            <input type="date" id="fechaExamen" name="fechaExamen" required>
          </div>
          <div class="form-row">
            <label for="presentarAnte">Presentar Ante *</label>
            <input type="text" id="presentarAnte" name="presentarAnte" required maxlength="200" placeholder="Ej: Universidad, Empresa, etc.">
          </div>
          <div class="form-row">
            <button type="submit" class="button button--primary">Solicitar constancia</button>
          </div>
          <div id="solicitudMsg"></div>
        </form>
      </div>
    </div>

    <script>
    function recargarTablaSolicitudes(page = 1) {
        fetch('modules/Constancias de Examen/studentView/includes/table.php?gibbonPersonID=<?= $gibbonPersonID ?>&page=' + page)
            .then(resp => resp.text())
            .then(html => {
                document.getElementById('solicitudesTableContainer').innerHTML = html;
            });
    }
    document.addEventListener('DOMContentLoaded', function() {
        recargarTablaSolicitudes(1);

        // Event delegation for pagination links
        document.getElementById('solicitudesTableContainer').addEventListener('click', function(e) {
            if (e.target.matches('.page-link')) {
                e.preventDefault();
                const page = e.target.getAttribute('data-page');
                recargarTablaSolicitudes(page);
            }
        });
        
        // Modal logic
        var modal = document.getElementById('modalConstancia');
        var btn = document.getElementById('abrirModalConstancia');
        var span = document.getElementById('cerrarModalConstancia');
        if (btn) {
            btn.onclick = function() {
                modal.style.display = 'block';
            };
        }
        if (span) {
            span.onclick = function() {
                modal.style.display = 'none';
                document.getElementById('constanciaRequestForm').reset();
                document.getElementById('solicitudMsg').innerHTML = '';
            };
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
                document.getElementById('constanciaRequestForm').reset();
                document.getElementById('solicitudMsg').innerHTML = '';
            }
        };
        // Form submit
        document.getElementById('constanciaRequestForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            fetch('modules/Constancias de Examen/studentView/includes/submit.php', {
                method: 'POST',
                body: formData
            })
            .then(resp => resp.json())
            .then(data => {
                const msgDiv = document.getElementById('solicitudMsg');
                msgDiv.innerHTML = data.message;
                msgDiv.className = data.success ? 'alert alert-success' : 'alert alert-danger';
                if (data.success) {
                    recargarTablaSolicitudes(1);
                    form.reset();
                    setTimeout(() => { 
                        modal.style.display = 'none'; 
                        msgDiv.innerHTML = '';
                        msgDiv.className = '';
                    }, 1200);
                }
            })
            .catch(() => {
                const msgDiv = document.getElementById('solicitudMsg');
                msgDiv.innerHTML = 'Error inesperado al enviar la solicitud.';
                msgDiv.className = 'alert alert-danger';
            });
        });
    });
    </script>

    <script>
    // Autocomplete functionality
    document.addEventListener('DOMContentLoaded', function() {
        const materiaInput = document.getElementById('materia');
        const autocompleteDropdown = document.getElementById('materiaAutocomplete');
        let selectedIndex = -1;
        let courses = [];

        // Debounce function to limit API calls
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Search courses function
        const searchCourses = debounce(function(searchTerm) {
            if (searchTerm.length < 2) {
                autocompleteDropdown.style.display = 'none';
                return;
            }

            fetch(`modules/Constancias de Examen/studentView/includes/search_courses.php?q=${encodeURIComponent(searchTerm)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        
                        if (data.error) {
                            return [];
                        }
                        
                        if (!Array.isArray(data)) {
                            return [];
                        }
                        
                        courses = data;
                        displayResults(data);
                    } catch (parseError) {
                        return [];
                    }
                })
                .catch(error => {
                    console.error('Error searching courses:', error);
                });
        }, 300);

        // Display search results
        function displayResults(results) {
            autocompleteDropdown.innerHTML = '';
            
            if (!Array.isArray(results)) {
                autocompleteDropdown.style.display = 'none';
                return;
            }
            
            if (results.length === 0) {
                autocompleteDropdown.style.display = 'none';
                return;
            }
            
            results.forEach((course, index) => {
                const item = document.createElement('div');
                item.className = 'autocomplete-item';
                
                // Mostrar código solo si existe
                if (course.code && course.code.trim() !== '') {
                    item.innerHTML = `
                        <span class="course-name">${course.name}</span>
                        <span class="course-code">${course.code}</span>
                    `;
                } else {
                    item.innerHTML = `
                        <span class="course-name">${course.name}</span>
                    `;
                }
                
                item.addEventListener('click', () => {
                    selectCourse(course);
                });
                
                item.addEventListener('mouseenter', () => {
                    selectedIndex = index;
                    updateSelection();
                });
                
                autocompleteDropdown.appendChild(item);
            });
            
            autocompleteDropdown.style.display = 'block';
            selectedIndex = -1;
        }

        // Select a course
        function selectCourse(course) {
            materiaInput.value = course.name;
            autocompleteDropdown.style.display = 'none';
            selectedIndex = -1;
        }

        // Update selection with keyboard
        function updateSelection() {
            const items = autocompleteDropdown.querySelectorAll('.autocomplete-item');
            items.forEach((item, index) => {
                item.classList.toggle('selected', index === selectedIndex);
            });
        }

        // Handle input events
        materiaInput.addEventListener('input', function() {
            const searchTerm = this.value.trim();
            searchCourses(searchTerm);
        });

        // Handle keyboard navigation
        materiaInput.addEventListener('keydown', function(e) {
            const items = autocompleteDropdown.querySelectorAll('.autocomplete-item');
            
            if (items.length === 0) return;
            
            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                    updateSelection();
                    break;
                    
                case 'ArrowUp':
                    e.preventDefault();
                    selectedIndex = Math.max(selectedIndex - 1, -1);
                    updateSelection();
                    break;
                    
                case 'Enter':
                    e.preventDefault();
                    if (selectedIndex >= 0 && courses[selectedIndex]) {
                        selectCourse(courses[selectedIndex]);
                    }
                    break;
                    
                case 'Escape':
                    autocompleteDropdown.style.display = 'none';
                    selectedIndex = -1;
                    break;
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!materiaInput.contains(e.target) && !autocompleteDropdown.contains(e.target)) {
                autocompleteDropdown.style.display = 'none';
                selectedIndex = -1;
            }
        });

        // Clear autocomplete when modal is closed
        document.getElementById('cerrarModalConstancia').addEventListener('click', function() {
            materiaInput.value = '';
            autocompleteDropdown.style.display = 'none';
            selectedIndex = -1;
        });
    });
    </script>
    <?php
    echo '</div>'; // Close wrapper div
} 