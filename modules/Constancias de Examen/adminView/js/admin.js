document.addEventListener('DOMContentLoaded', function() {
    // Inicializar todos los formularios de subida
    initializeUploadForms();
});

function initializeUploadForms() {
    // Encontrar todos los inputs de archivo
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(function(input) {
        const formId = input.closest('form').id;
        const constanciaId = formId.replace('uploadForm', '');
        
        // Agregar event listener para cambio de archivo
        input.addEventListener('change', function(e) {
            handleFileSelection(e, constanciaId);
        });
    });
    
    // Agregar event listeners para los botones de envío
    const submitButtons = document.querySelectorAll('.upload-submit-btn');
    submitButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const formId = this.getAttribute('data-form-id');
            submitForm(formId);
        });
    });
}

function handleFileSelection(event, constanciaId) {
    const file = event.target.files[0];
    const formId = 'uploadForm' + constanciaId;
    const form = document.getElementById(formId);
    const uploadLabel = document.getElementById('uploadLabel' + constanciaId);
    const submitButton = document.querySelector(`[data-form-id="${formId}"]`);
    
    if (file) {
        // Validar que sea un PDF
        if (file.type !== 'application/pdf') {
            alert('Por favor selecciona un archivo PDF.');
            event.target.value = '';
            return;
        }
        
        // Validar tamaño (máximo 10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('El archivo es demasiado grande. Máximo 10MB.');
            event.target.value = '';
            return;
        }
        
        // Cambiar el texto del botón de subida
        const buttonText = uploadLabel.querySelector('.button-text');
        const uploadIcon = uploadLabel.querySelector('.upload-icon');
        const checkIcon = uploadLabel.querySelector('.check-icon');
        
        // Mostrar nombre del archivo (truncado si es muy largo)
        const fileName = file.name.length > 20 ? file.name.substring(0, 17) + '...' : file.name;
        buttonText.textContent = fileName;
        
        // Cambiar icono
        uploadIcon.style.display = 'none';
        checkIcon.style.display = 'inline-block';
        
        // Cambiar estilo del botón
        uploadLabel.querySelector('.button--upload').style.backgroundColor = '#d4edda';
        uploadLabel.querySelector('.button--upload').style.color = '#155724';
        uploadLabel.querySelector('.button--upload').style.borderColor = '#c3e6cb';
        
        // Habilitar botón de envío
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.textContent = 'Enviar';
        }
        
        // Agregar clase para indicar que hay archivo seleccionado
        form.classList.add('file-selected');
        
    } else {
        // Resetear si no hay archivo
        resetUploadForm(constanciaId);
    }
}

function resetUploadForm(constanciaId) {
    const formId = 'uploadForm' + constanciaId;
    const form = document.getElementById(formId);
    const uploadLabel = document.getElementById('uploadLabel' + constanciaId);
    const submitButton = document.querySelector(`[data-form-id="${formId}"]`);
    
    if (uploadLabel) {
        const buttonText = uploadLabel.querySelector('.button-text');
        const uploadIcon = uploadLabel.querySelector('.upload-icon');
        const checkIcon = uploadLabel.querySelector('.check-icon');
        
        buttonText.textContent = 'Subir PDF';
        uploadIcon.style.display = 'inline-block';
        checkIcon.style.display = 'none';
        
        uploadLabel.querySelector('.button--upload').style.backgroundColor = 'white';
        uploadLabel.querySelector('.button--upload').style.color = '#935EE1';
        uploadLabel.querySelector('.button--upload').style.borderColor = '#935EE1';
    }
    
    if (submitButton) {
        submitButton.disabled = true;
        submitButton.textContent = 'Enviar';
    }
    
    form.classList.remove('file-selected');
}

function submitForm(formId) {
    const form = document.getElementById(formId);
    const submitButton = document.querySelector(`[data-form-id="${formId}"]`);
    const fileInput = form.querySelector('input[type="file"]');
    
    if (!fileInput.files[0]) {
        alert('Por favor selecciona un archivo PDF primero.');
        return;
    }
    
    // Deshabilitar botón durante el envío
    submitButton.disabled = true;
    submitButton.textContent = 'Enviando...';
    
    // Mostrar indicador de carga
    const originalContent = submitButton.innerHTML;
    submitButton.innerHTML = '<span class="loading-spinner"></span> Enviando...';
    
    // Crear FormData para envío AJAX
    const formData = new FormData(form);
    formData.append('submit', '1');
    
    // Enviar via AJAX
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Verificar si la respuesta contiene mensajes de éxito o error
        if (data.includes('success') || data.includes('éxito') || data.includes('correctamente')) {
            // Mostrar mensaje de éxito
            showMessage('Constancia subida correctamente', 'success');
            // Recargar la página después de un breve delay
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else if (data.includes('error') || data.includes('Error')) {
            // Mostrar mensaje de error
            showMessage('Error al subir la constancia', 'error');
            // Resetear el formulario
            resetUploadForm(formId.replace('uploadForm', ''));
        } else {
            // Respuesta inesperada, recargar la página
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error de conexión', 'error');
        // Resetear el formulario
        resetUploadForm(formId.replace('uploadForm', ''));
    })
    .finally(() => {
        // Restaurar el botón
        submitButton.disabled = false;
        submitButton.textContent = 'Enviar';
        submitButton.innerHTML = originalContent;
    });
}

// Función para mostrar mensajes
function showMessage(message, type) {
    // Crear elemento de mensaje
    const messageDiv = document.createElement('div');
    messageDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'}`;
    messageDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        padding: 15px 20px;
        border-radius: 6px;
        color: white;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideIn 0.3s ease-out;
    `;
    
    if (type === 'success') {
        messageDiv.style.backgroundColor = '#28a745';
    } else {
        messageDiv.style.backgroundColor = '#dc3545';
    }
    
    messageDiv.textContent = message;
    
    // Agregar al DOM
    document.body.appendChild(messageDiv);
    
    // Remover después de 3 segundos
    setTimeout(() => {
        messageDiv.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.parentNode.removeChild(messageDiv);
            }
        }, 300);
    }, 3000);
}

// Agregar estilos para las animaciones de mensajes
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Función para recargar la página después de un envío exitoso
function reloadPageAfterSuccess() {
    setTimeout(function() {
        window.location.reload();
    }, 2000);
}

// Detectar si hay mensajes de éxito en la página
document.addEventListener('DOMContentLoaded', function() {
    const successMessages = document.querySelectorAll('.success');
    if (successMessages.length > 0) {
        reloadPageAfterSuccess();
    }
}); 