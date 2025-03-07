<?php

require_once './visor/assets/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();

$html = '

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">

<head>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Contenido plantilla WhatsApp</title>
    <style>
        .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
        }

        .table th,
        .table td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
        }

        .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
        }

        .table tbody + tbody {
        border-top: 2px solid #dee2e6;
        }

        .table-sm th,
        .table-sm td {
        padding: 0.3rem;
        }

        .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
        }

        .titles {
            width: 50%;
        }

    </style>
</head>

<body>

    <div>
        <table class="table table-striped" >
            <tbody>
                <tr>
                    <th class="titles">Fecha de envio: </th>
                    <td>'.$messageInfo['fecha'].'</td>
                </tr>
                <tr>
                    <th>Proveedor:</th>
                    <td>'.$messageInfo['proveedor'].'</td>
                </tr>
                <tr>
                    <th>Numero Origen:</th>
                    <td>'.$messageInfo['origen'].'</td>
                </tr>
                <tr>
                    <th>Numero destino: </th>
                    <td>'.$messageInfo['destino'].'</td>
                </tr>
                <tr>
                    <th>Texto Enviado:</th>
                    <td>'.$message.'</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
';

$dompdf->loadHtml($html);
// Render the HTML as PDF
$dompdf->render();
// Output the generated PDF to Browser
$dompdf->stream();
?>