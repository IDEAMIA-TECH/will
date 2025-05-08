// Función para cargar las secciones del diagnóstico
function cargarSecciones() {
    $.ajax({
        url: 'includes/get_secciones.php',
        method: 'GET',
        success: function(response) {
            $('#seccionesDiagnostico').html(response);
            inicializarCalificaciones();
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar secciones:', error);
            mostrarAlerta('Error al cargar las secciones', 'danger');
        }
    });
}

// Función para inicializar las calificaciones
function inicializarCalificaciones() {
    $('.calificacion-input').on('change', function() {
        const seccionId = $(this).data('seccion');
        actualizarPuntuacionSeccion(seccionId);
    });
}

// Función para actualizar la puntuación de una sección
function actualizarPuntuacionSeccion(seccionId) {
    let total = 0;
    $(`.calificacion-input[data-seccion="${seccionId}"]:checked`).each(function() {
        total += parseInt($(this).val());
    });
    
    $(`#puntuacion-seccion-${seccionId}`).text(total);
    actualizarPuntuacionTotal();
}

// Función para actualizar la puntuación total
function actualizarPuntuacionTotal() {
    let total = 0;
    $('.puntuacion-seccion').each(function() {
        total += parseInt($(this).text());
    });
    
    const porcentaje = (total / 100) * 100;
    $('#puntuacion-total').text(total);
    $('#porcentaje-total').text(porcentaje.toFixed(1) + '%');
    
    // Actualizar barra de progreso
    $('.progress-bar').css('width', porcentaje + '%');
}

// Función para guardar borrador
function guardarBorrador() {
    const formData = new FormData($('#diagnosticoForm')[0]);
    formData.append('es_borrador', '1');
    
    $.ajax({
        url: 'includes/guardar_diagnostico.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            mostrarAlerta('Borrador guardado exitosamente', 'success');
        },
        error: function(xhr, status, error) {
            console.error('Error al guardar borrador:', error);
            mostrarAlerta('Error al guardar el borrador', 'danger');
        }
    });
}

// Función para mostrar alertas
function mostrarAlerta(mensaje, tipo) {
    const alerta = `
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    $('#alertas').html(alerta);
    setTimeout(() => {
        $('.alert').alert('close');
    }, 5000);
}

// Función para validar el formulario
function validarFormulario() {
    let valido = true;
    
    // Validar campos requeridos
    $('#diagnosticoForm [required]').each(function() {
        if (!$(this).val()) {
            valido = false;
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    // Validar que todas las preguntas tengan calificación
    $('.seccion-diagnostico').each(function() {
        const seccionId = $(this).data('seccion');
        if ($(`.calificacion-input[data-seccion="${seccionId}"]:checked`).length === 0) {
            valido = false;
            $(this).addClass('border-danger');
        } else {
            $(this).removeClass('border-danger');
        }
    });
    
    return valido;
}

// Event Listeners
$(document).ready(function() {
    // Cargar secciones al iniciar
    cargarSecciones();
    
    // Validar formulario antes de enviar
    $('#diagnosticoForm').on('submit', function(e) {
        if (!validarFormulario()) {
            e.preventDefault();
            mostrarAlerta('Por favor complete todos los campos requeridos', 'warning');
        }
    });
    
    // Inicializar tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}); 