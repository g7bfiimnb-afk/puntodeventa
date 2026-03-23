<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-lg" style="width: 400px; border-radius: 15px;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <i class="fas fa-user-circle fa-4x text-primary"></i>
                <h3 class="mt-2">Acceso al Sistema</h3>
            </div>
            <form id="form_login">
                <div class="form-group">
                    <label>Usuario</label>
                    <input type="text" name="usuario" class="form-control" required autofocus>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-lg">Entrar</button>
            </form>
            <div id="mensaje_login" class="mt-3"></div>
        </div>
    </div>
</div>