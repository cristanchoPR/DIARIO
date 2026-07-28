# Módulo de Inventarios Físicos Multisede - Aldia ERP

Este proyecto implementa el Módulo de Inventarios Físicos Multisede para Aldia ERP, desarrollado con un stack moderno y reactivo utilizando **Laravel 11, Livewire 3 y Alpine.js**.

---

## Requisitos Previos

Asegúrate de contar con lo siguiente en tu entorno local:
* **PHP** >= 8.2 (Recomendado 8.3+)
* **Composer**
* **Node.js** & **npm**

---

## Instalación y Configuración Rápida

Sigue estos pasos para ejecutar el proyecto localmente:

1. **Instalar Dependencias de PHP:**
   ```bash
   composer install
   ```

2. **Instalar Dependencias de Frontend:**
   ```bash
   npm install
   ```

3. **Configurar el Entorno (.env):**
   Copia el archivo de configuración de ejemplo:
   ```bash
   copy .env.example .env
   ```
   *Nota: Por defecto, el proyecto está configurado para utilizar SQLite, por lo que creará automáticamente el archivo de base de datos `database/database.sqlite`.*

4. **Generar la Clave de Aplicación:**
   ```bash
   php artisan key:generate
   ```

5. **Ejecutar Migraciones y Seeders (Cargar Datos de Prueba):**
   Este comando creará todas las tablas del ERP y cargará 15 productos reales de supermercado con stock simulado en 3 sedes diferentes, así como los roles y usuarios de prueba.
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Compilar Recursos Frontend:**
   ```bash
   npm run build
   ```
   *(O usa `npm run dev` para desarrollo en vivo)*

7. **Iniciar Servidor Local:**
   ```bash
   php artisan serve
   ```
   Ahora puedes abrir tu navegador en [http://localhost:8000](http://localhost:8000).

---

## Cuentas de Acceso de Prueba

Puedes probar el flujo completo ingresando con cualquiera de estas cuentas:

* **Administrador (Acceso Total):**
  * **Email:** `admin@aldia.com`
  * **Contraseña:** `password`
  
* **Operario de Bodega (Foco en Conteo Físico):**
  * **Email:** `operario@aldia.com`
  * **Contraseña:** `password`

---

## Características Implementadas

1. **Gestión de Sedes:** Listado con filtros multisede y avance porcentual de conteo.
2. **Carga Automática de Productos:** Inicializa detalles congelando existencias y costos al momento de inicio del conteo.
3. **Planilla de Conteo Rápida:**
   * Reactividad inmediata con Alpine.js para multiplicaciones fila por fila y totales generales.
   * Conteo Ciego opcional.
   * Navegación fluida por teclado (`Enter`, flechas arriba/abajo).
   * Modo alto contraste para bodegas.
4. **Flujo de Estados:** Transición segura: *En elaboración* ➔ *Guardado* ➔ *Finalizado* ➔ *Aplicado*.
5. **Auditoría Permanente:** Bitácora inmutable que rastrea cada cambio de cantidad o costo por usuario.
6. **Movimientos de Ajuste:** Generación automática de ajustes positivos/negativos al aplicar un inventario.
7. **Exportaciones:** Reportes listos para descargar en formatos **PDF** y **Excel**.
