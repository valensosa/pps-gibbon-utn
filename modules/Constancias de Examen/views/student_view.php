<?php
// Add CSS
echo "<link rel='stylesheet' type='text/css' href='" . $this->session->get('absoluteURL') . "/modules/Constancias de Examen/studentView/css/student.css' />";
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">';

$gibbonPersonID = $this->session->get('gibbonPersonID');
?>

<div class="constancias-module">
    <div id="solicitudesTableContainer"></div>
    <div id="solicitudMsg"></div>
    
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

    document.getElementById('solicitudesTableContainer').addEventListener('click', function(e) {
        if (e.target.matches('.page-link')) {
            e.preventDefault();
            const page = e.target.getAttribute('data-page');
            recargarTablaSolicitudes(page);
        }
    });
    
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
    
    // Autocomplete functionality
    const materiaInput = document.getElementById('materia');
    const autocompleteDropdown = document.getElementById('materiaAutocomplete');
    let selectedIndex = -1;
    let courses = [];

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

    const searchCourses = debounce(function(searchTerm) {
        if (searchTerm.length < 2) {
            autocompleteDropdown.style.display = 'none';
            return;
        }

        fetch(`modules/Constancias de Examen/studentView/includes/search_courses.php?q=${encodeURIComponent(searchTerm)}`)
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.text();
            })
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    if (data.error || !Array.isArray(data)) return [];
                    courses = data;
                    displayResults(data);
                } catch (parseError) {
                    return [];
                }
            })
            .catch(error => console.error('Error searching courses:', error));
    }, 300);

    function displayResults(results) {
        autocompleteDropdown.innerHTML = '';
        if (!Array.isArray(results) || results.length === 0) {
            autocompleteDropdown.style.display = 'none';
            return;
        }
        
        results.forEach((course, index) => {
            const item = document.createElement('div');
            item.className = 'autocomplete-item';
            
            if (course.code && course.code.trim() !== '') {
                item.innerHTML = `
                    <span class="course-name">${course.name}</span>
                    <span class="course-code">${course.code}</span>
                `;
            } else {
                item.innerHTML = `<span class="course-name">${course.name}</span>`;
            }
            
            item.addEventListener('click', () => selectCourse(course));
            item.addEventListener('mouseenter', () => {
                selectedIndex = index;
                updateSelection();
            });
            
            autocompleteDropdown.appendChild(item);
        });
        
        autocompleteDropdown.style.display = 'block';
        selectedIndex = -1;
    }

    function selectCourse(course) {
        materiaInput.value = course.name;
        autocompleteDropdown.style.display = 'none';
        selectedIndex = -1;
    }

    function updateSelection() {
        const items = autocompleteDropdown.querySelectorAll('.autocomplete-item');
        items.forEach((item, index) => {
            item.classList.toggle('selected', index === selectedIndex);
        });
    }

    materiaInput.addEventListener('input', function() {
        searchCourses(this.value.trim());
    });

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

    document.addEventListener('click', function(e) {
        if (!materiaInput.contains(e.target) && !autocompleteDropdown.contains(e.target)) {
            autocompleteDropdown.style.display = 'none';
            selectedIndex = -1;
        }
    });

    document.getElementById('cerrarModalConstancia').addEventListener('click', function() {
        materiaInput.value = '';
        autocompleteDropdown.style.display = 'none';
        selectedIndex = -1;
    });
});
</script>