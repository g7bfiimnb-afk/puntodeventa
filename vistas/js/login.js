$('#form_login_ajax').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: 'ajax/LoginAjax.php',
        method: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        success: function(res) {
            try {
                if(res.res == "success") {
                    // Si es comprador, lo mandamos directo a su interfaz
                    if(res.rol == "comprador") {
                        window.location.href = "index.php?p=comprador";
                    } else {
                        window.location.href = "index.php?p=inicio";
                    }
                } else {
                    alert(res.msj);
                }
            } catch (error) {
                console.error("Error al procesar la respuesta del servidor:", r);
                alert("Error en el formato de respuesta del servidor.");
            }
        },
        error: function() {
            alert("No se pudo conectar con el servidor.");
        }
    });
});