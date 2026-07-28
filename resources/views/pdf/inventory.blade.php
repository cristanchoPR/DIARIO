<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Inventario Físico - Aldia ERP</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1b2437;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #16305c;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo-container {
            float: left;
        }
        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #16305c;
        }
        .logo-sub {
            background-color: #f3d9ae;
            color: #16305c;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 3px;
            margin-left: 5px;
            font-weight: bold;
        }
        .report-title {
            float: right;
            text-align: right;
        }
        .report-title h1 {
            margin: 0;
            font-size: 18px;
            color: #16305c;
        }
        .report-title p {
            margin: 5px 0 0 0;
            color: #6b7686;
        }
        .clearfix {
            clear: both;
        }
        .info-grid {
            margin-bottom: 20px;
            background-color: #f7f9fc;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #e3e8f0;
        }
        .info-col {
            float: left;
            width: 50%;
        }
        .info-label {
            font-weight: bold;
            color: #6b7686;
        }
        .info-value {
            color: #1b2437;
        }
        .metrics-grid {
            margin-bottom: 25px;
        }
        .metric-card {
            float: left;
            width: 22%;
            background-color: #ffffff;
            border: 1px solid #e3e8f0;
            padding: 10px;
            border-radius: 6px;
            margin-right: 2%;
            text-align: center;
        }
        .metric-card.last {
            margin-right: 0;
        }
        .metric-title {
            font-size: 9px;
            text-transform: uppercase;
            color: #6b7686;
            font-weight: bold;
        }
        .metric-val {
            font-size: 14px;
            font-weight: bold;
            color: #16305c;
            margin-top: 4px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th {
            background-color: #16305c;
            color: #ffffff;
            font-weight: bold;
            padding: 8px;
            text-align: left;
            font-size: 9px;
        }
        .table td {
            padding: 8px;
            border-bottom: 1px solid #e3e8f0;
        }
        .table tr:nth-child(even) {
            background-color: #f7f9fc;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge-positive {
            color: #2fae7a;
            font-weight: bold;
        }
        .badge-negative {
            color: #e5584d;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo-container">
            <img src="{{ public_path('images/logo_aldia_oficial_cropped.png') }}" alt="Aldia Oficial" style="max-height: 70px;">
        </div>
        <div class="report-title">
            <h1>Inventario Físico Aplicado</h1>
            <p>ID: INV-{{ str_pad($inventario->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="info-grid">
        <div class="info-col">
            <div><span class="info-label">Nombre del Inventario:</span> <span class="info-value">{{ $inventario->nombre }}</span></div>
            <div><span class="info-label">Sede:</span> <span class="info-value">{{ $inventario->sede->nombre }}</span></div>
            <div><span class="info-label">Responsable:</span> <span class="info-value">{{ $inventario->usuario->name }}</span></div>
        </div>
        <div class="info-col">
            <div><span class="info-label">Fecha Inicio:</span> <span class="info-value">{{ $inventario->fecha_creacion->format('d/m/Y h:i A') }}</span></div>
            <div><span class="info-label">Fecha Aplicación:</span> <span class="info-value">{{ $inventario->fecha_aplicacion->format('d/m/Y h:i A') }}</span></div>
            <div><span class="info-label">Estado:</span> <span class="info-value" style="color: #2fae7a; font-weight: bold;">Aplicado</span></div>
        </div>
        <div class="clearfix"></div>
    </div>

    @php
        $detalles = $inventario->detalles;
        $totalSku = $detalles->count();
        $skuContados = $detalles->whereNotNull('cantidad_fisica')->count();
        $unidadesSistema = $detalles->sum('existencia_sistema');
        $unidadesContadas = $detalles->sum('cantidad_fisica');
        $valorSistema = $detalles->sum(function($d) { return $d->existencia_sistema * $d->costo_sistema; });
        $valorContado = $detalles->sum('valor_total');
        $diffDinero = $valorContado - $valorSistema;
        $diffUnidades = $unidadesContadas - $unidadesSistema;
    @endphp

    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-title">SKUs Contados</div>
            <div class="metric-val">{{ $skuContados }} / {{ $totalSku }}</div>
        </div>
        <div class="metric-card">
            <div class="metric-title">Unidades Contadas</div>
            <div class="metric-val">{{ number_format($unidadesContadas, 2) }}</div>
        </div>
        <div class="metric-card">
            <div class="metric-title">Valor Contado</div>
            <div class="metric-val">${{ number_format($valorContado, 2) }}</div>
        </div>
        <div class="metric-card last">
            <div class="metric-title">Diferencia Neta</div>
            <div class="metric-val" style="color: {{ $diffDinero >= 0 ? '#2fae7a' : '#e5584d' }}">
                {{ $diffDinero >= 0 ? '+' : '' }}${{ number_format($diffDinero, 2) }}
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Producto</th>
                <th class="text-right">Sist. Stock</th>
                <th class="text-right">Sist. Costo</th>
                <th class="text-right">Físico Qty</th>
                <th class="text-right">Físico Costo</th>
                <th class="text-right">Total Contado</th>
                <th class="text-right">Diferencia</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $det)
                @php
                    $diffU = $det->cantidad_fisica !== null ? ($det->cantidad_fisica - $det->existencia_sistema) : 0;
                    $diffD = $det->cantidad_fisica !== null ? ($det->valor_total - ($det->existencia_sistema * $det->costo_sistema)) : 0;
                @endphp
                <tr>
                    <td>{{ $det->producto->codigo }}</td>
                    <td>
                        <strong>{{ $det->producto->nombre }}</strong><br>
                        <span style="color: #6b7686; font-size: 8px;">{{ $det->producto->categoria }} | Marca: {{ $det->producto->marca }}</span>
                    </td>
                    <td class="text-right">{{ number_format($det->existencia_sistema, 2) }}</td>
                    <td class="text-right">${{ number_format($det->costo_sistema, 2) }}</td>
                    <td class="text-right">{{ $det->cantidad_fisica !== null ? number_format($det->cantidad_fisica, 2) : '-' }}</td>
                    <td class="text-right">${{ number_format($det->costo_contado ?? $det->costo_sistema, 2) }}</td>
                    <td class="text-right">${{ number_format($det->valor_total, 2) }}</td>
                    <td class="text-right font-bold">
                        @if ($det->cantidad_fisica !== null)
                            <span class="{{ $diffU > 0 ? 'badge-positive' : ($diffU < 0 ? 'badge-negative' : '') }}">
                                {{ $diffU > 0 ? '+' : '' }}{{ number_format($diffU, 2) }}
                            </span>
                            <span style="display:block; font-size: 8px; color: {{ $diffD >= 0 ? '#2fae7a' : '#e5584d' }}">
                                {{ $diffD >= 0 ? '+' : '' }}${{ number_format($diffD, 2) }}
                            </span>
                        @else
                            No Contado
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
