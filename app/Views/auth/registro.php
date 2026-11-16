<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Registro de nuevo usuario - JDM Build & Shop Management System">
    <title>JDM Shop - Crear Cuenta</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0b0d0f;
            color: #f1f3f5;
            min-height: 100vh;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
        }

        .auth-container {
            width: 100%;
            max-width: 460px;
        }

        .auth-card {
            background: #14171a;
            border: 1px solid #232830;
            border-radius: 12px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.6);
        }

        .form-control-dark {
            background-color: #0d0f12;
            border: 1px solid #282e36;
            color: #f8f9fa;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, background-color 0.15s ease-in-out;
        }

        .form-control-dark:focus {
            background-color: #101317;
            border-color: #dc3545;
            color: #ffffff;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.22);
        }

        .form-control-dark::placeholder {
            color: #555e68;
        }

        .brand-badge {
            display: inline-block;
            background: #dc3545;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.72rem;
            letter-spacing: 0.12em;
            padding: 3px 8px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .btn-jdm {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #ffffff;
            font-weight: 600;
            letter-spacing: 0.03em;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-jdm:hover {
            background-color: #bb2d3b;
            border-color: #b02a37;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .btn-jdm:active {
            transform: translateY(0);
        }

        .auth-link {
            color: #dc3545;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.15s ease;
        }

        .auth-link:hover {
            color: #ff6b6b;
            text-decoration: underline;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">
    <div class="auth-container">
        <!-- Brand Header -->
        <div class="text-center mb-4">
            <span class="brand-badge mb-2">Tuning &amp; Performance</span>
            <h1 class="h3 fw-bold tracking-wide mt-2 mb-1 text-light">JDM SHOP</h1>
            <p class="text-secondary small mb-0">Build &amp; Shop Management System</p>
        </div>

        <!-- Auth Card -->
        <div class="card auth-card text-light p-4">
            <div class="card-body p-0">
                <h2 class="h5 fw-bold mb-1 text-light">Crear Cuenta</h2>
                <p class="text-secondary small mb-4">Registra tus datos de usuario para unirte a JDM Shop</p>

                <!-- Flash Alerts -->
                <?php if ($error = session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger border-0 bg-danger-subtle text-danger-emphasis py-2 px-3 small rounded-3 mb-3" role="alert">
                        <?= esc($error) ?>
                    </div>
                <?php endif; ?>

                <?php if ($errors = session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger border-0 bg-danger-subtle text-danger-emphasis py-2 px-3 small rounded-3 mb-3" role="alert">
                        <ul class="mb-0 ps-3">
                            <?php foreach ($errors as $message): ?>
                                <li><?= esc($message) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('registrar') ?>" method="POST" autocomplete="on">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="nombre" class="form-label text-secondary small fw-semibold text-uppercase">Nombre Completo</label>
                        <input
                            type="text"
                            class="form-control form-control-dark"
                            id="nombre"
                            name="nombre"
                            placeholder="Ej. Keisuke Takahashi"
                            value="<?= esc(old('nombre')) ?>"
                            required
                            autofocus
                        >
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label text-secondary small fw-semibold text-uppercase">Correo Electrónico</label>
                        <input
                            type="email"
                            class="form-control form-control-dark"
                            id="email"
                            name="email"
                            placeholder="nombre@ejemplo.com"
                            value="<?= esc(old('email')) ?>"
                            required
                        >
                    </div>

                    <div class="row g-2 mb-4">
                        <div class="col-12 col-sm-6">
                            <label for="password" class="form-label text-secondary small fw-semibold text-uppercase">Contraseña</label>
                            <input
                                type="password"
                                class="form-control form-control-dark"
                                id="password"
                                name="password"
                                placeholder="Mínimo 6 caracteres"
                                required
                            >
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="password_confirm" class="form-label text-secondary small fw-semibold text-uppercase">Confirmar</label>
                            <input
                                type="password"
                                class="form-control form-control-dark"
                                id="password_confirm"
                                name="password_confirm"
                                placeholder="Repite contraseña"
                                required
                            >
                        </div>
                    </div>

                    <button type="submit" class="btn btn-jdm w-100 mb-3">
                        Registrar Cuenta
                    </button>
                </form>

                <div class="text-center pt-2 border-top border-secondary border-opacity-25 mt-3">
                    <p class="text-secondary small mb-0">
                        ¿Ya tienes una cuenta?
                        <a href="<?= base_url('acceso') ?>" class="auth-link">Iniciar Sesión</a>
                    </p>
                </div>
            </div>
        </div>

        <p class="text-center text-secondary small mt-4 mb-0 opacity-50">
            &copy; <?= date('Y') ?> JDM Shop. Todos los derechos reservados.
        </p>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

