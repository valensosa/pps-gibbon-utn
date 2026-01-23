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
require_once __DIR__ . '/DependencyFactory.php';

use NotasUTNAPI\Infrastructure\Repository\GibbonAlumnoRepository;
use NotasUTNAPI\DependencyFactory;

$page->breadcrumbs->add(__('Notas de Estudiantes API'));

// Obtener el rol y el gibbonPersonID del usuario actual
$gibbonPersonID = $session->get('gibbonPersonID');
$userDNI = null;

// Obtener el rol del usuario usando las nuevas queries
$userRole = null;
if ($gibbonPersonID) {
    $userRole = GibbonAlumnoRepository::getUserRole($connection2, $gibbonPersonID);
}

// Inicialización de capas mediante Factory
$controller = DependencyFactory::createControladorNotas($connection2);

// Filtros por GET
$selectedStudentDni = $_GET['student_dni'] ?? '';

// Determinar qué DNI buscar
$targetDNI = null;

if ($userRole === 'Student') {
    // Si es estudiante, buscar su DNI en el sistema de documentos personales
    $userDNI = GibbonAlumnoRepository::getStudentDNI($connection2, $gibbonPersonID);
    
    if (!$userDNI) {
        $page->addError(__('No se encontró un DNI registrado en el sistema. Por favor, contacte a la administración.'));
        return;
    }
    $targetDNI = $userDNI;
} elseif ($selectedStudentDni) {
    // Si es admin y seleccionó un estudiante
    $targetDNI = $selectedStudentDni;
}

// Procesar solicitud si hay un DNI objetivo
$result = null;
if ($targetDNI) {
    $pageParam = $_GET['page_' . $targetDNI] ?? 1;
    $request = [
        'student_dni' => $targetDNI,
        'page' => $pageParam
    ];
    $result = $controller->handleRequest($request);
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
        <?php 
        if ($result && isset($result['success']) && $result['success']) {
            $student = $result['student'];
            $pagination = $result['pagination'];
            require __DIR__ . '/views/TablaNotas.php';
        } elseif ($result && isset($result['error'])) {
            echo '<div class="alert alert-warning">' . htmlspecialchars($result['error']) . '</div>';
        }
        ?>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- Custom CSS -->
<link rel="stylesheet" href="<?= $session->get('absoluteURL') ?>/layers/views/css/notas.css">

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
    
    fetch('layers/NotasEndpoint.php?student_dni=' + encodeURIComponent(dni) + '&page=' + page)
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

        fetch(`layers/NotasEndpoint.php?action=search&q=${encodeURIComponent(searchTerm)}`)
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
            
            fetch('layers/NotasEndpoint.php?student_dni=' + encodeURIComponent(dni))
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