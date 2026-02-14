<?php
require_once("functions.php");

$mensaje = "";

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pagar'])) {
    $resultado = registrarCompra($_POST);
    $clase = $resultado['status'] ? 'alert-success' : 'alert-danger';
    $mensaje = "<div class='alert $clase alert-dismissible fade show' role='alert'>
                    {$resultado['msg']}
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                </div>";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasarela de Pago Segura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Métodos de Pago</h4>
                    </div>
                    <div class="card-body">
                        <?php echo $mensaje; ?>

                        <form action="form_pay.php" method="POST" id="purchaseForm">
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Día de visita</label>
                                    <select name="dia" id="dia" class="form-select" required>
                                        <option value="lunes">Lunes</option>
                                        <option value="martes">Martes</option>
                                        <option value="miercoles">Miércoles</option>
                                        <option value="jueves">Jueves</option>
                                        <option value="viernes">Viernes</option>
                                        <option value="sabado">Sábado</option>
                                        <option value="domingo">Domingo</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tipo de tarjeta</label>
                                    <select name="metod" id="metod" class="form-select" required>
                                        <option value="Visa">Visa</option>
                                        <option value="Mastercard">Mastercard</option>
                                        <option value="JCB">JCB</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Número de tarjeta</label>
                                    <input type="text" name="tarjeta" class="form-control" placeholder="0000000000000000" pattern="[0-9]{13,19}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Mes</label>
                                    <select name="mes" class="form-select" required>
                                        <?php for ($i = 1; $i <= 12; $i++) printf("<option value='%02d'>%02d</option>", $i, $i); ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Año</label>
                                    <select name="anio" class="form-select" required>
                                        <?php
                                        $year = (int)date("Y");
                                        for ($i = 0; $i <= 15; $i++) echo "<option value='" . ($year + $i) . "'>" . ($year + $i) . "</option>";
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label">Código de seguridad (CVV)</label>
                                    <input type="text" name="cvv" class="form-control" placeholder="000" pattern="[0-9]{3,4}" required>
                                </div>
                            </div>

                            <h5 class="border-bottom pb-2 mb-3">Información de Facturación</h5>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" name="nombre" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Apellidos</label>
                                    <input type="text" name="apellidos" class="form-control" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Localidad</label>
                                    <input type="text" name="localidad" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Código Postal</label>
                                    <input type="text" name="cp" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Dirección de facturación</label>
                                <input type="text" name="direccion" class="form-control" required>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="save" id="save">
                                <label class="form-check-label" for="save">
                                    Guardar mi información de pago para la próxima vez
                                </label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="pagar" class="btn btn-primary btn-lg">Realizar Pago</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>