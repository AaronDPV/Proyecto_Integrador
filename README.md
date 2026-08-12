# 🏢 ERP Portugal

> **ERP Portugal** es una plataforma web de planificación de recursos empresariales (ERP) desarrollada íntegramente con **Laravel**. Está diseñada para modernizar, centralizar y automatizar la gestión operativa, administrativa y comercial de la organización.

---

## 🚀 Características Principales

- **Panel de Administración (Dashboard):** Panel de control para la visualización de métricas, indicadores clave y gestión del sistema.
- **Autenticación, Roles y Permisos:** Sistema de acceso y control granular para distintos perfiles de usuario.
- **Gestión Comercial y Operativa:** Módulos para el control de inventario, procesos de compra, órdenes de venta y seguimiento de clientes.
- **Lógica de Notificaciones:** Sistema de alertas y notificaciones internas del negocio.
- **Diseño Responsive:** Interfaz adaptable a dispositivos móviles, tablets y escritorio.

---

## 🛠️ Tecnologías Utilizadas

- **Backend Framework:** PHP, Laravel (Blade, Eloquent ORM, Controllers, Migrations)
- **Frontend / Estilos:** HTML5, CSS3, JavaScript, Bootstrap / Tailwind CSS
- **Base de Datos:** MySQL / PostgreSQL
- **Control de Versiones:** Git

---

## 📋 Requisitos Previos

Asegúrate de contar con lo siguiente instalado en tu entorno local:

- **PHP** >= 8.1 / 8.2
- **Composer**
- **Node.js** y **NPM**
- **MySQL** / **PostgreSQL**

---

## ⚙️ Instalación y Configuración

1. **Clonar el repositorio:**
   ```bash
   git clone [https://github.com/tu-usuario/erp-portugal.git](https://github.com/tu-usuario/erp-portugal.git)
   cd erp-portugal
Instalar dependencias de PHP con Composer:

composer install
Instalar dependencias de JavaScript y CSS:

npm install
npm run dev
Copiar y configurar el archivo de entorno .env:

cp .env.example .env
Ajusta las credenciales de tu base de datos en el archivo .env:

Fragmento de código
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=erp_portugal
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

Iniciar el servidor de desarrollo:

php artisan serve
Abre tu navegador e ingresa a http://127.0.0.1:8000.
