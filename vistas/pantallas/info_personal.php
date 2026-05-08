<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Cliente - Punto de Venta</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-info text-white text-center">
                        <h4 class="mb-0">
                            <i class="fas fa-user-edit mr-2"></i> Información del Cliente
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <form id="form_info_personal_pago">
                            <!-- Identificación del Usuario -->
                            <div class="card mb-3 border-info">
                                <div class="card-header bg-info text-white">
                                    <i class="fas fa-id-card mr-2"></i> Identificación del Usuario
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="cliente_nombre">Nombre Completo *</label>
                                                <input type="text" class="form-control form-control-lg" id="cliente_nombre" value="Victor Eduardo Lopez Soriano" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="cliente_id_usuario">Nombre de Usuario (ID)</label>
                                                <input type="text" class="form-control form-control-lg" id="cliente_id_usuario" value="HAEFCDBGH13783" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="cliente_nombre_elegido">Nombre Elegido (Display Name)</label>
                                                <input type="text" class="form-control form-control-lg" id="cliente_nombre_elegido" value="Victor Eduardo Lopez">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="cliente_ocupacion">Ocupación</label>
                                                <input type="text" class="form-control form-control-lg" id="cliente_ocupacion" value="Estudiante">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Datos de Facturación (Fiscales) -->
                            <div class="card mb-3 border-secondary">
                                <div class="card-header bg-secondary text-white">
                                    <i class="fas fa-file-invoice-dollar mr-2"></i> Datos de Facturación (Fiscales)
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="cliente_rfc">RFC</label>
                                                <input type="text" class="form-control form-control-lg" id="cliente_rfc" placeholder="Opcional o CURP">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="cliente_situacion_fiscal">Situación Fiscal</label>
                                                <input type="text" class="form-control form-control-lg" id="cliente_situacion_fiscal" placeholder="Opcional">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Información de Contacto -->
                            <div class="card mb-3 border-success">
                                <div class="card-header bg-success text-white">
                                    <i class="fas fa-address-book mr-2"></i> Información de Contacto
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="cliente_email">Correo Electrónico *</label>
                                                <input type="email" class="form-control form-control-lg" id="cliente_email" value="el638883@gmail.com" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="cliente_telefono">Teléfono Celular *</label>
                                                <input type="tel" class="form-control form-control-lg" id="cliente_telefono" value="+52 638883" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold" for="cliente_direccion">Dirección del Domicilio</label>
                                        <input type="text" class="form-control form-control-lg" id="cliente_direccion" placeholder="Calle, número, colonia, ciudad">
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold" for="cliente_referencias">Referencias del Domicilio</label>
                                        <textarea class="form-control form-control-lg" id="cliente_referencias" rows="3" placeholder="Puntos de referencia, referencias adicionales, puertas, entre calles"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Método de Pago -->
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <i class="fas fa-credit-card mr-2"></i> Método de Pago
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Selecciona tu método de pago *</label>
                                        <div class="custom-control custom-radio mb-2">
                                            <input type="radio" class="custom-control-input" id="pago_efectivo" name="metodo_pago" value="efectivo" checked>
                                            <label class="custom-control-label" for="pago_efectivo">
                                                <i class="fas fa-money-bill-wave text-success"></i> Efectivo
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio mb-2">
                                            <input type="radio" class="custom-control-input" id="pago_tarjeta" name="metodo_pago" value="tarjeta_credito">
                                            <label class="custom-control-label" for="pago_tarjeta">
                                                <i class="fas fa-credit-card text-primary"></i> Tarjeta de Crédito/Débito
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio mb-2">
                                            <input type="radio" class="custom-control-input" id="pago_transferencia" name="metodo_pago" value="transferencia">
                                            <label class="custom-control-label" for="pago_transferencia">
                                                <i class="fas fa-exchange-alt text-warning"></i> Transferencia Bancaria
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bg-light text-center">
                        <button type="button" class="btn btn-secondary btn-lg mr-3" onclick="window.close()">
                            <i class="fas fa-times mr-2"></i> Cancelar
                        </button>
                        <button type="button" class="btn btn-success btn-lg" id="btn_finalizar_compra">
                            <i class="fas fa-check-circle mr-2"></i> Finalizar Compra
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Cargar datos del localStorage si existen
            let carritoComprador = JSON.parse(localStorage.getItem('carritoComprador') || '[]');
            let total = parseFloat(localStorage.getItem('totalCompra') || '0');

            $('#btn_finalizar_compra').on('click', function() {
                // Validar que los campos requeridos estén completos
                let nombre = $('#cliente_nombre').val().trim();
                let idUsuario = $('#cliente_id_usuario').val().trim();
                let nombreElegido = $('#cliente_nombre_elegido').val().trim();
                let ocupacion = $('#cliente_ocupacion').val().trim();
                let email = $('#cliente_email').val().trim();
                let telefono = $('#cliente_telefono').val().trim();
                let rfc = $('#cliente_rfc').val().trim();
                let situacionFiscal = $('#cliente_situacion_fiscal').val().trim();
                let direccion = $('#cliente_direccion').val().trim();
                let referencias = $('#cliente_referencias').val().trim();
                let metodo_pago = $('input[name="metodo_pago"]:checked').val();

                if(!nombre) {
                    alert("Por favor, ingresa tu nombre completo");
                    $('#cliente_nombre').focus();
                    return;
                }

                if(!email) {
                    alert("Por favor, ingresa tu correo electrónico");
                    $('#cliente_email').focus();
                    return;
                }

                if(!telefono) {
                    alert("Por favor, ingresa tu teléfono");
                    $('#cliente_telefono').focus();
                    return;
                }

                $.ajax({
                    url: '../../ajax/VentaAjax.php',
                    method: 'POST',
                    data: {
                        productos_venta: JSON.stringify(carritoComprador),
                        total_venta: total,
                        monto_recibido: total,
                        cambio: 0,
                        cliente_nombre: nombre,
                        cliente_id_usuario: idUsuario,
                        cliente_nombre_elegido: nombreElegido,
                        cliente_ocupacion: ocupacion,
                        cliente_email: email,
                        cliente_telefono: telefono,
                        cliente_rfc: rfc,
                        cliente_situacion_fiscal: situacionFiscal,
                        cliente_direccion: direccion,
                        cliente_referencias: referencias,
                        metodo_pago: metodo_pago
                    },
                    success: function(r) {
                        try {
                            let res = JSON.parse(r);
                            if (res.res == "success") {
                                alert('Compra procesada correctamente');
                                // Limpiar localStorage
                                localStorage.removeItem('carritoComprador');
                                localStorage.removeItem('totalCompra');
                                // Cerrar la ventana
                                window.close();
                            } else {
                                alert("✗ Error: " + res.msj);
                            }
                        } catch (e) {
                            console.error("Error:", r);
                            alert("Respuesta inesperada del servidor.");
                        }
                    },
                    error: function() {
                        alert("Error al procesar la compra.");
                    }
                });
            });
        });
    </script>
</body>
</html>