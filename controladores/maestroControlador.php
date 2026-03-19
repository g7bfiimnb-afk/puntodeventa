<div class="container-fluid bg-white p-3 shadow-sm">
    <div class="d-flex flex-wrap gap-2 mb-3 bg-light p-2 border">
        <button class="btn btn-outline-secondary btn-sm text-center">
            <i class="fas fa-users d-block mb-1 text-primary"></i> <small>Clientes</small>
        </button>
        <button class="btn btn-outline-secondary btn-sm text-center">
            <i class="fas fa-plus-circle d-block mb-1 text-success"></i> <small>Nuevo</small>
        </button>
        <button class="btn btn-outline-secondary btn-sm text-center">
            <i class="fas fa-minus-circle d-block mb-1 text-warning"></i> <small>Descuento</small>
        </button>
        <button class="btn btn-outline-secondary btn-sm text-center">
            <i class="fas fa-trash d-block mb-1 text-danger"></i> <small>Eliminar</small>
        </button>
        <button class="btn btn-primary btn-sm text-center ms-auto">
            <i class="fas fa-money-bill-wave d-block mb-1"></i> <strong>F1 - COBRAR</strong>
        </button>
    </div>

    <div class="row g-3 mb-3 p-2 border-bottom">
        <div class="col-md-2">
            <label class="form-label small fw-bold">Folio:</label>
            <input type="text" class="form-control form-control-sm" value="000001" readonly>
        </div>
        <div class="col-md-6">
            <label class="form-label small fw-bold">Código de Barras:</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                <input type="text" class="form-control" placeholder="Escanea o escribe el código...">
                <button class="btn btn-secondary">Alt + S</button>
            </div>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-bold">Cant:</label>
            <input type="number" class="form-control form-control-sm" value="1.0">
        </div>
        <div class="col-md-2 text-end">
             <h4 class="text-success mb-0">Total: $0.00</h4>
        </div>
    </div>

    <div class="table-responsive" style="height: 400px; overflow-y: auto;">
        <table class="table table-sm table-striped table-hover border">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Stock</th>
                    <th>Precio</th>
                    <th>Cant.</th>
                    <th>Subtotal</th>
                    <th>Importe</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>75010001</td>
                    <td>CHIPS A LA DIABLA BARCEL 41GR</td>
                    <td class="text-danger">-1</td>
                    <td>$15.00</td>
                    <td>1</td>
                    <td>$15.00</td>
                    <td>$15.00</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="row mt-3 bg-light p-2 border">
        <div class="col-md-4">
            <small class="fw-bold">Cliente:</small> <span class="badge bg-secondary">PÚBLICO EN GENERAL</span>
        </div>
        <div class="col-md-4 text-center">
            <small class="fw-bold">Artículos en carrito:</small> 1
        </div>
        <div class="col-md-4 text-end text-primary">
            <h2 class="fw-bold m-0">$ 15.00</h2>
        </div>
    </div>
</div>