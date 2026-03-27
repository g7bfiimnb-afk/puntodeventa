$(document).ready(function() {
    // Escuchamos el evento submit del formulario con id "form_login"
    $('#form_login').on('submit', function(e) {
        e.preventDefault(); // Evitamos que la página se recargue
        
        // Limpiamos mensajes previos
        $('#mensaje_login').html('');

        $.ajax({
            url: 'ajax/LoginAjax.php', // El archivo PHP que procesa el login
            method: 'POST',
            data: $(this).serialize(),
            success: function(respuesta) {
                try {
                    let res = JSON.parse(respuesta);
                    
                    if(res.res == "success") {
                        // REGLA DE REDIRECCIÓN POR ROL
                        // Si el rol es 'comprador', va a la vista tipo Mercado
                        if(res.rol == "comprador") {
                            window.location.href = "index.php?p=comprador";
                        } else {
                            // Si es 'admin', va al panel de control normal
                            window.location.href = "index.php?p=inicio";
                        }
                    } else {
                        // Si las credenciales son incorrectas
                        $('#mensaje_login').html('<div class="alert alert-danger text-center">' + res.msj + '</div>');
                    }
                } catch (error) {
                    console.error("Error al procesar la respuesta del servidor:", respuesta);
                    $('#mensaje_login').html('<div class="alert alert-warning text-center">Error en el formato de respuesta.</div>');
                }
            },
            error: function() {
                $('#mensaje_login').html('<div class="alert alert-danger text-center">No se pudo conectar con el servidor.</div>');
            }
        });
    });
});