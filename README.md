Aquí tienes todo el contenido en un solo bloque de texto plano para que puedas copiarlo y pegarlo directamente en tu archivo README.md:

Markdown
<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# LMS Systematic

Sistema LMS desarrollado para la empresa **Systematic** con el objetivo de optimizar la gestión académica, centralizar contenidos educativos y mejorar el seguimiento del aprendizaje de los estudiantes.

---

## 📝 Descripción del Proyecto

Este proyecto busca implementar una plataforma LMS (Learning Management System) para la empresa Systematic, una institución dedicada a la capacitación tecnológica y formación profesional en Perú.

### Características Principales:
* **Centralizar** materiales educativos.
* **Automatizar** procesos académicos.
* **Mejorar** el seguimiento del progreso de los estudiantes.
* **Generar** reportes y certificaciones.
* **Facilitar** la gestión de cursos y docentes.

---

## 🛠️ Tecnologías Utilizadas

* **Backend:** PHP >= 8.2 & Laravel
* **Base de Datos:** MySQL
* **Frontend:** Node.js & NPM *(Opcional)*

---

## 📋 Requisitos Previos

Antes de ejecutar el proyecto, asegúrate de tener instalado:
* PHP >= 8.2
* Composer
* MySQL
* Node.js y NPM
* Git

---

## 🚀 Instalación y Configuración

### 1. Clonar el repositorio
```bash
git clone [https://github.com/ElLeoZalds/LMS-SYSTEMATIC.git](https://github.com/ElLeoZalds/LMS-SYSTEMATIC.git)
```
### 2. Entrar al directorio del proyecto
```bash
cd LMS-SYSTEMATIC
```
### 3. Instalar dependencias de PHP
```bash
composer install
```
### 4. Configurar el archivo de entorno
```bash
cp .env.example .env
```
Abra el archivo .env y configure sus credenciales de base de datos:

Fragmento de código
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_db
DB_USERNAME=root
DB_PASSWORD=
```
### 5. Generar la clave de la aplicación
```bash
php artisan key:generate
```
## 🗄️ Migraciones y Seeders
Ejecutar migraciones
```bash
php artisan migrate
```
Ejecutar seeders
```bash
php artisan db:seed
```
Ejecutar todo junto (Migraciones + Seeders)
```bash
php artisan migrate:fresh --seed
```
## 💻 Ejecución del Proyecto
Antes de levantar el servidor, limpia la caché de la aplicación y genera el enlace simbólico para el almacenamiento de archivos:

```bash
php artisan optimize:clear
php artisan storage:link
```
Iniciar el servidor de desarrollo
```bash
php artisan serve
```
El proyecto estará disponible en: http://127.0.0.1:8000