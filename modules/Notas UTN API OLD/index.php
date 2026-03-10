<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

//Module includes
require_once $session->get('absolutePath').'/modules/Notas UTN API/moduleFunctions.php';

$page->breadcrumbs->add(__('Notas de Estudiantes API'));

// Obtener el rol y el gibbonPersonID del usuario actual
$gibbonPersonID = $session->get('gibbonPersonID');
$userDNI = null;

// Obtener el rol del usuario usando las nuevas queries
$userRole = null;
if ($gibbonPersonID) {
    $userRole = GibbonQueries::getUserRole($connection2, $gibbonPersonID);
}

// Si es estudiante, buscar su DNI en el sistema de documentos personales
if ($userRole === 'Student' && $gibbonPersonID) {
    $userDNI = getStudentDNI($gibbonPersonID);
}

// Filtros por GET
$selectedStudentDni = $_GET['student_dni'] ?? '';
$selectedSubject = $_GET['subject'] ?? '';

$students = [];

// Filtrado para estudiantes
if ($userRole === 'Student') {
    if (!$userDNI) {
        $page->addError(__('No se encontró un DNI registrado en el sistema. Por favor, contacte a la administración.'));
        return;
    }
    
    $apiData = getStudentDataFromAPI($userDNI);
    if ($apiData) {
        $studentData = formatStudentData($apiData, $userDNI);
        if ($studentData) {
            // Filtro por materia si corresponde
            if ($selectedSubject && $selectedSubject !== 'all') {
                $studentData['materias'] = array_filter($studentData['materias'], function($mat) use ($selectedSubject) {
                    return $mat['nombre'] === $selectedSubject;
                });
            }
            $students[] = $studentData;
        }
    }
}
// Filtrado para no-estudiantes
else if ($userRole !== 'Student') {
    if ($selectedStudentDni) {
        $apiData = getStudentDataFromAPI($selectedStudentDni);
        if ($apiData) {
            $studentData = formatStudentData($apiData, $selectedStudentDni);
            if ($studentData) {
                $students[] = $studentData;
            }
        }
    }
}

// Mostrar buscador antes de la validación de datos
?>
<div class="content notas-module">
    <h1 style="margin: 0 0 1em 0;">Notas de Estudiantes API</h1>
    <?php if ($userRole !== 'Student'): ?>
        <form id="studentFilterForm" method="get" class="search-form">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <div class="autocomplete-container">
                    <input type="text" name="student_dni" id="student_dni" value="<?= htmlspecialchars($selectedStudentDni) ?>" placeholder="Buscar por nombre, apellido o DNI..." class="search-input" autocomplete="off">
                    <div id="studentAutocomplete" class="autocomplete-dropdown"></div>
                </div>
            </div>
            <button type="submit" class="button button--primary">Buscar</button>
        </form>
        <div id="ajaxNotasContainer"></div>
    <?php endif; ?>
    <div id="gradesTableContainer">
        <?php foreach ($students as $student): ?>
            <?php
            // Ordenar materias por fecha (descendente - más reciente primero)
            $materias = $student['materias'];
            usort($materias, function($a, $b) {
                $fechaA = strtotime($a['fecha'] ?? '1970-01-01');
                $fechaB = strtotime($b['fecha'] ?? '1970-01-01');
                return $fechaB <=> $fechaA; // Descendente
            });
            
            // Filtrar solo materias con actividad_nombre
            $materiasConActividad = array_filter($materias, function($materia) {
                return !empty($materia['actividad_nombre']);
            });
            
            // Paginación
            $materiasPorPagina = 10;
            $totalMaterias = count($materiasConActividad);
            $totalPaginas = ceil($totalMaterias / $materiasPorPagina);
            $paginaActual = $_GET['page_' . $student['dni']] ?? 1;
            $paginaActual = max(1, min($paginaActual, $totalPaginas));
            $offset = ($paginaActual - 1) * $materiasPorPagina;
            $materiasPaginadas = array_slice($materiasConActividad, $offset, $materiasPorPagina);
            ?>
            
            <div class="content-block student-block" data-dni="<?= htmlspecialchars($student['dni']) ?>">
                <h2><?php echo htmlspecialchars($student['nombre'] . ' ' . $student['apellido'] . ' - ' . $student['dni']); ?></h2>
                
                <?php if ($totalMaterias > 0): ?>
                    <div class="table-responsive">
                        <?php
                        echo '<table class="table table-striped table-bordered grades-table">';
                        echo '<thead><tr>';
                        echo '<th>Título Araucano</th>';
                        echo '<th>Título Nombre</th>';
                        echo '<th>Plan Vigente</th>';
                        echo '<th>Actividad</th>';
                        echo '<th>Código</th>';
                        echo '<th>Fecha</th>';
                        echo '<th>Nota</th>';
                        echo '<th>Resultado</th>';
                        echo '<th>Promedio</th>';
                        echo '<th>Forma Aprobación</th>';
                        echo '<th>Optativa</th>';
                        echo '</tr></thead><tbody>';
                        
                        foreach ($materiasPaginadas as $materia) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($materia['titulo_araucano'] ?? '') . '</td>';
                            echo '<td>' . htmlspecialchars($materia['titulo_nombre'] ?? '') . '</td>';
                            echo '<td>' . htmlspecialchars($materia['plan_vigente'] ?? '') . '</td>';
                            echo '<td>' . htmlspecialchars($materia['actividad_nombre'] ?? '') . '</td>';
                            echo '<td>' . htmlspecialchars($materia['actividad_codigo'] ?? '') . '</td>';
                            echo '<td>' . htmlspecialchars($materia['fecha'] ?? '') . '</td>';
                            echo '<td>' . htmlspecialchars($materia['nota'] ?? '') . '</td>';
                            echo '<td>' . htmlspecialchars($materia['resultado'] ?? '') . '</td>';
                            echo '<td>' . htmlspecialchars($materia['promedio'] ?? '') . '</td>';
                            echo '<td>' . htmlspecialchars($materia['forma_aprobacion'] ?? '') . '</td>';
                            echo '<td>' . htmlspecialchars($materia['es_optativa'] ?? '') . '</td>';
                            echo '</tr>';
                        }
                        
                        echo '</tbody></table>';
                        ?>
                    </div>
                    
                    <?php if ($totalPaginas > 1): ?>
                        <div class="pagination-controls">
                            <?php
                            $queryParams = $_GET;
                            
                            if ($paginaActual > 1) {
                                $queryParams['page_' . $student['dni']] = $paginaActual - 1;
                                echo '<a href="?' . http_build_query($queryParams) . '" class="button">&laquo; Anterior</a>';
                            }
                            
                            for ($i = 1; $i <= $totalPaginas; $i++) {
                                $queryParams['page_' . $student['dni']] = $i;
                                $activeClass = ($i == $paginaActual) ? 'active' : '';
                                echo '<a href="?' . http_build_query($queryParams) . '" class="button ' . $activeClass . '">' . $i . '</a>';
                            }
                            
                            if ($paginaActual < $totalPaginas) {
                                $queryParams['page_' . $student['dni']] = $paginaActual + 1;
                                echo '<a href="?' . http_build_query($queryParams) . '" class="button">Siguiente &raquo;</a>';
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <div style="text-align: center; margin-top: 10px; color: #6c757d; font-size: 0.9rem;">
                        Mostrando <?= $offset + 1 ?>-<?= min($offset + $materiasPorPagina, $totalMaterias) ?> de <?= $totalMaterias ?> materias
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 20px; color: #6c757d;">
                        No se encontraron materias con actividades para este estudiante.
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- Custom CSS -->
<link rel="stylesheet" href="<?= $session->get('absoluteURL') ?>/modules/Notas UTN API/css/notas.css">

<script>
// Global function for pagination
function loadPage(page) {
    const dni = document.getElementById('student_dni').value.trim();
    const container = document.getElementById('ajaxNotasContainer');
    
    if (!container || !dni) {
        console.error('Contenedor o DNI no encontrado');
        return;
    }
    
    container.innerHTML = '<div class="alert alert-info">Cargando página...</div>';
    
    fetch('modules/Notas UTN API/buscarNotas.php?student_dni=' + encodeURIComponent(dni) + '&page=' + page)
        .then(resp => resp.text())
        .then(html => {
            container.innerHTML = html;
        })
        .catch((error) => {
            console.error('Error al cargar la página:', error);
            container.innerHTML = '<div class="alert alert-danger">Error al cargar la página.</div>';
        });
}

// Autocomplete functionality
document.addEventListener('DOMContentLoaded', function() {
    const studentInput = document.getElementById('student_dni');
    const autocompleteDropdown = document.getElementById('studentAutocomplete');
    let selectedIndex = -1;
    let students = [];

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

    // Search students function
    const searchStudents = debounce(function(searchTerm) {
        if (searchTerm.length < 2) {
            autocompleteDropdown.style.display = 'none';
            return;
        }

        fetch(`modules/Notas UTN API/search_students.php?q=${encodeURIComponent(searchTerm)}`)
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
                        console.error('Error:', data.error);
                        return [];
                    }
                    
                    if (!Array.isArray(data)) {
                        return [];
                    }
                    
                    students = data;
                    displayResults(data);
                } catch (parseError) {
                    console.error('Error parsing JSON:', parseError);
                    return [];
                }
            })
            .catch(error => {
                console.error('Error searching students:', error);
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
        
        results.forEach((student, index) => {
            const item = document.createElement('div');
            item.className = 'autocomplete-item';
            
            item.textContent = student.display;
            
            item.addEventListener('click', () => {
                selectStudent(student);
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

    // Select a student
    function selectStudent(student) {
        studentInput.value = student.dni;
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
    studentInput.addEventListener('input', function() {
        const searchTerm = this.value.trim();
        searchStudents(searchTerm);
    });

    // Handle keyboard navigation
    studentInput.addEventListener('keydown', function(e) {
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
                if (selectedIndex >= 0 && students[selectedIndex]) {
                    selectStudent(students[selectedIndex]);
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
        if (!studentInput.contains(e.target) && !autocompleteDropdown.contains(e.target)) {
            autocompleteDropdown.style.display = 'none';
            selectedIndex = -1;
        }
    });

    // Handle form submission
    const form = document.getElementById('studentFilterForm');
    const container = document.getElementById('ajaxNotasContainer');
    
    if (form && container) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const dni = document.getElementById('student_dni').value.trim();
            if (!dni) return;
            
            container.innerHTML = '<div class="alert alert-info">Buscando notas...</div>';
            
            fetch('modules/Notas UTN API/buscarNotas.php?student_dni=' + encodeURIComponent(dni))
                .then(resp => resp.text())
                .then(html => {
                    container.innerHTML = html;
                })
                .catch(() => {
                    container.innerHTML = '<div class="alert alert-danger">Error al buscar notas.</div>';
                });
        });
    }
});
</script>

<style>
.grades-table thead tr,
.grades-table thead th,
.grades-table thead tr th {
    background-color: #935EE1 !important;
    color: #fff !important;
}
.grades-table td, .grades-table th {
    text-align: center;
}
</style> 