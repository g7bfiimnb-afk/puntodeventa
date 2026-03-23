$(document).ready(function() {
    $('#form_login_ajax').on('submit', function(e) {
        e.preventDefault(); // IMPORTANTE: Evita que el formulario recargue la página
        
        let datos = $(this).serialize();

        $.ajax({
            url: 'ajax/LoginAjax.php',
            method: 'POST',
            data: datos,
            success: function(respuesta) {
                let res = JSON.parse(respuesta);
                if(res.res == "success") {
                    window.location.href = "index.php"; // Recarga para entrar al Dashboard
                } else {
                    alert(res.msj);
                }
            }
        });
    });
});