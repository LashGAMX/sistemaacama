# ==========================================
# 🧩 Script para dividir archivo SQL pesado
# Tabla objetivo: fotos_recepcion
# Crea 4 partes + script .bat para importarlas
# ==========================================

# 📁 Ruta del archivo original y carpeta de salida
$inputFile = "C:\Users\Administrador\Documents\SPLIT\archivo.sql"
$outputPath = "C:\Users\Administrador\Documents\SPLIT\"

# 🧱 Archivo para CREATE TABLE
$createTableFile = Join-Path $outputPath "fotos_recepcion_create.sql"

# Arrays para guardar contenido
$contenidoCreate = @()
$lineasInsert = @()
$capturandoCreate = $false

Write-Host "🔍 Leyendo archivo y extrayendo información..."

# Leer archivo línea por línea
Get-Content $inputFile | ForEach-Object {
    $linea = $_

    # Detectar inicio de CREATE TABLE fotos_recepcion
    if (-not $capturandoCreate -and $linea -match 'CREATE TABLE `fotos_recepcion`') {
        $capturandoCreate = $true
    }

    # Capturar CREATE TABLE hasta el punto y coma final
    if ($capturandoCreate) {
        $contenidoCreate += $linea
        if ($linea -match ';$') {
            $capturandoCreate = $false
        }
    }

    # Capturar INSERT INTO fotos_recepcion
    if ($linea -match '^INSERT INTO `fotos_recepcion`') {
        $lineasInsert += $linea
    }
}

# Guardar archivo con CREATE TABLE
if ($contenidoCreate.Count -gt 0) {
    $contenidoCreate | Out-File $createTableFile -Encoding utf8
    Write-Host "✅ Archivo CREATE TABLE guardado en: $createTableFile"
} else {
    Write-Host "⚠️ No se encontró estructura CREATE TABLE para fotos_recepcion."
}

# Dividir INSERT INTO en 4 partes iguales
$totalLineas = $lineasInsert.Count
if ($totalLineas -gt 0) {
    $lineasPorParte = [math]::Ceiling($totalLineas / 4)
    Write-Host "📊 Total de líneas INSERT: $totalLineas → $lineasPorParte por parte aprox."

    for ($i = 0; $i -lt 4; $i++) {
        $inicio = $i * $lineasPorParte
        $fin = [math]::Min((($i + 1) * $lineasPorParte) - 1, $totalLineas - 1)
        $parte = $lineasInsert[$inicio..$fin]

        if ($parte.Count -gt 0) {
            $archivoParte = Join-Path $outputPath ("fotos_recepcion_parte_$($i + 1).sql")
            $parte | Out-File $archivoParte -Encoding utf8
            Write-Host "💾 Parte $($i + 1) guardada en: $archivoParte"
        }
    }

    Write-Host "✅ División completada. Archivos guardados en $outputPath"
} else {
    Write-Host "⚠️ No se encontraron líneas INSERT INTO para fotos_recepcion."
}

# Crear archivo .bat para importar fácilmente las partes
$batFile = Join-Path $outputPath "importar_partes.bat"
$nombreDB = Read-Host "👉 Ingresa el nombre de tu base de datos MySQL"
$usuario = Read-Host "👉 Ingresa tu usuario MySQL (por defecto: root)"
if ([string]::IsNullOrWhiteSpace($usuario)) { $usuario = "root" }

$batContenido = @"
@echo off
echo ============================================
echo  IMPORTADOR AUTOMATICO - fotos_recepcion
echo ============================================

mysql -u $usuario -p $nombreDB < "fotos_recepcion_create.sql"
mysql -u $usuario -p $nombreDB < "fotos_recepcion_parte_1.sql"
mysql -u $usuario -p $nombreDB < "fotos_recepcion_parte_2.sql"
mysql -u $usuario -p $nombreDB < "fotos_recepcion_parte_3.sql"
mysql -u $usuario -p $nombreDB < "fotos_recepcion_parte_4.sql"

echo ============================================
echo  ✅ Importación completada con éxito
echo ============================================
pause
"@

$batContenido | Out-File $batFile -Encoding ascii
Write-Host "`n⚙️ Archivo BAT generado en: $batFile"
Write-Host "➡️ Ejecuta ese archivo para importar automáticamente las 4 partes."
