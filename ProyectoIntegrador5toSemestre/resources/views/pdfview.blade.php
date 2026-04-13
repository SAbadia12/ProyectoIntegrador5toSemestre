<!DOCTYPE html>
<html>

<head>
    <title>Exportación PDF</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        *, *::before, *::after {
            box-sizing: border-box;
        }
        html,
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            color: #e2e8f0;
            background: #02060f;
            width: 100%;
            overflow-x: hidden;
        }

        body {
            padding: 0;
        }

        .pdf-frame {
            display: block;
            width: calc(100% - 30px);
            max-width: 210mm;
            margin: 0 auto;
            padding: 15px;
            background: linear-gradient(180deg, #02060f 0%, #0b1425 100%);
        }

        .pdf-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.18), rgba(11, 23, 42, 0.96));
            border: 1px solid rgba(56, 114, 191, 0.36);
            margin-bottom: 24px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-logo {
            display: inline-block;
            width: 130px;
            height: auto;
            border-radius: 18px;
            border: 1px solid rgba(56, 114, 191, 0.48);
        }

        .brand-copy {
            display: flex;
            flex-direction: column;
            gap: 2px;
            white-space: nowrap;
        }

        .brand-title {
            margin: 0;
            font-size: 22px;
            color: #38bdf8;
        }

        .brand-table {
            width: auto;
            border: none;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
        }

        .brand-table td {
            padding: 0;
            border: none;
            vertical-align: middle;
        }

        .brand-table .brand-logo-cell {
            width: auto;
            padding-right: 8px;
        }

        .brand-table .brand-text-cell {
            padding: 0;
        }

        .brand-subtitle {
            margin: 0;
            font-size: 12px;
            color: #94a3b8;
        }

        .hero {
            padding: 20px;
            background: linear-gradient(180deg, rgba(56, 114, 191, 0.18), rgba(15, 23, 42, 0.95));
            border-radius: 18px;
            color: #f8fafc;
            border: 1px solid rgba(56, 114, 191, 0.28);
            margin-bottom: 24px;
        }

        .hero h1 {
            margin: 0;
            font-size: 28px;
            color: #38bdf8;
        }

        .hero p {
            margin: 10px 0 0;
            color: #cbd5e1;
            font-size: 13px;
            line-height: 1.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            table-layout: fixed;
        }
        th, td {
            word-wrap: break-word;
        }

        th,
        td {
            border: 1px solid rgba(226, 232, 240, 0.18);
            padding: 10px 12px;
            text-align: left;
        }

        th {
            background: rgba(56, 114, 191, 0.20);
            color: #c7d2fe;
            font-weight: 700;
        }

        tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.03);
        }

        td {
            color: #e2e8f0;
        }

        .pdf-footer {
            font-size: 11px;
            color: #94a3b8;
            text-align: right;
            margin-top: 18px;
        }
    </style>
</head>

<body>
    <div class="pdf-frame">
        <div class="pdf-header">
            <!--<div class="brand">
                <img src="{{ public_path('img/logoSISC.jpeg') }}" alt="Logo SISC" class="brand-logo">
                <div class="brand-copy">
                    <p class="brand-title">SISC Administrador</p>
                    <p class="brand-subtitle">Exportación oficial del módulo de datos</p>
                </div>
            </div>
            <div style="text-align:right;">
                <p style="margin:0;font-size:12px;color:#94a3b8;">Fecha: {{ now()->setTimezone('America/Bogota')->format('d/m/Y') }}</p>
            </div>-->
            <table class="brand-table">
                <tr>
                    <td class="brand-logo-cell">
                        <img src="{{ public_path('img/logoSISC.jpeg') }}" alt="Logo SISC" class="brand-logo">
                    </td>
                    <td class="brand-text-cell">
                        <p style="margin:0; font-size:22px; color:#38bdf8;">SISC (Sistema Informativo de Seguridad Ciudadana)</p>
                        <p style="margin:0; font-size:12px; color:#94a3b8;">Exportación de datos</p>
                    </td>
                </tr>
            </table>
            <div style="text-align:right;">
                <p style="margin:0;font-size:12px;color:#94a3b8;">Fecha: {{ now()->setTimezone('America/Bogota')->format('d/m/Y') }}</p>
            </div>
        </div>
        <div class="hero">
            <h1>{{ $titulo }}</h1>
        </div>
        <table>
            <thead>
                <tr>
                    @foreach($columnas as $columna)
                    <th>{{ $columna }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($registros as $registro)
                <tr>
                    @foreach($atributos as $atributo)
                    <td>{{ $registro->$atributo }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pdf-footer">Generado desde SISC - administrador web</div>
    </div>
</body>

</html>