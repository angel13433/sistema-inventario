# 📦 Sistema de Gestión de Inventario (Bimoneda USD / VES)

Sistema web integral de gestión y control de inventarios desarrollado con **Laravel 12**, **Vue 3**, **Inertia.js**, **Tailwind CSS** y **PostgreSQL**, con soporte para bimoneda (**Dólares USD** y **Bolívares VES** calculados según la tasa oficial del Banco Central de Venezuela - BCV).

---

## 🚀 Características Principales

- **Panel de Control (Dashboard):**
  - Métricas clave en tiempo real: total de productos, alertas de stock mínimo y productos agotados.
  - Valorización total del inventario en **USD** y **VES**.
  - Actualización dinámica de la **Tasa BCV** en tiempo real guardada en caché.
  - Monitor de productos críticos y feed con los últimos movimientos de stock.

- **Catálogo de Productos:**
  - Gestión completa (Crear, Ver, Editar, Desactivar).
  - Códigos SKU automáticos y manuales.
  - Conversión instantánea de precios USD a Bolívares.
  - Alertas visuales de stock y control de umbral mínimo.

- **Movimientos de Inventario Transaccionales:**
  - Registro seguro de **Entradas**, **Salidas** y **Ajustes de inventario**.
  - Validación estricta para evitar salidas por encima del stock disponible.
  - Auditoría completa: usuario responsable, cantidad anterior, cantidad resultante, motivo y observaciones.

- **Categorías y Proveedores:**
  - Organización de productos por categorías con conteo dinámico.
  - Directorio de proveedores con datos de contacto, RIF y teléfonos.

- **Interfaz de Usuario Moderna:**
  - Diseño responsive y fluido con tema oscuro optimizado.
  - Notificaciones flash interactivas y modales de confirmación.

---

## 🛠️ Stack Tecnológico

- **Backend:** [Laravel 12](https://laravel.com) (PHP 8.2+)
- **Frontend:** [Vue 3](https://vuejs.org) + [Inertia.js v2](https://inertiajs.com)
- **Estilos:** [Tailwind CSS](https://tailwindcss.com)
- **Base de Datos:** [PostgreSQL](https://www.postgresql.org) (Compatible con Supabase)
- **Empaquetador:** [Vite](https://vitejs.dev)

---

## ⚙️ Instalación y Configuración Local

### 1. Clonar el repositorio
```bash
git clone <URL_DEL_REPOSITORIO>
cd sistema-inventario
```

### 2. Instalar dependencias
```bash
composer install
npm install
```

### 3. Configurar variables de entorno
Copia el archivo `.env.example` a `.env`:
```bash
cp .env.example .env
php artisan key:generate
```

Configura tu conexión a la base de datos PostgreSQL en el archivo `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sistema_inventario
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña
```

*(Si utilizas **Supabase**, basta con colocar las credenciales del proyecto y `DB_SSLMODE=require`)*.

### 4. Ejecutar migraciones y sembrar datos de prueba
```bash
php artisan migrate --seed
```

> **Credenciales por defecto:**
> - **Usuario:** `admin@inventario.ve`
> - **Contraseña:** `password`

### 5. Compilar assets e iniciar servidores
```bash
# Terminal 1 (Servidor Laravel)
php artisan serve

# Terminal 2 (Vite HMR en desarrollo)
npm run dev
```

La aplicación estará accesible en: `http://localhost:8000`

---

## 🧪 Pruebas Unitarias
```bash
php artisan test
```

---

## 📄 Licencia
Este proyecto es de código abierto bajo la licencia [MIT](LICENSE).
