<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# LMS Systematic

Sistema LMS desarrollado para la empresa Systematic con el objetivo de optimizar la gestión académica, centralizar contenidos educativos y mejorar el seguimiento del aprendizaje de los estudiantes.

## Descripción del Proyecto

Este proyecto busca implementar una plataforma LMS (Learning Management System) para la empresa Systematic, una institución dedicada a la capacitación tecnológica y formación profesional.

El sistema permitirá:

- Centralizar materiales educativos
- Automatizar procesos académicos
- Mejorar el seguimiento del progreso de los estudiantes
- Generar reportes y certificaciones
- Facilitar la gestión de cursos y docentes

## Empresa

**Systematic**  
Empresa dedicada a la capacitación tecnológica y formación profesional en Perú.

## Tecnologías Utilizadas

- PHP
- Laravel
- MySQL

---

# Requisitos

Antes de ejecutar el proyecto, asegúrate de tener instalado:

- PHP >= 8.2
- Composer
- MySQL
- Laravel
- Node.js y NPM (opcional para frontend)
- Git

---

# Instalación del Proyecto

## 1. Clonar el repositorio

```bash
git clone https://github.com/ElLeoZalds/LMS-SYSTEMATIC.git
```

## 2. Entrar al proyecto

```bash
cd nombre-del-proyecto
```

## 3. Instalar dependencias de PHP

```bash
composer install
```

## 4. Copiar archivo de entorno

```bash
cp .env.example .env
```

## 5. Generar la application key

```bash
php artisan key:generate
```

## 6. Configurar la base de datos

Editar el archivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_db
DB_USERNAME=root
DB_PASSWORD=
```

---

# Migraciones y Seeders

## Ejecutar migraciones

```bash
php artisan migrate
```

## Ejecutar seeders

```bash
php artisan db:seed
```

## Ejecutar migraciones + seeders

```bash
php artisan migrate --seed
```

---

# Ejecutar el Proyecto

## Iniciar servidor Laravel

```bash
php artisan serve
```

El proyecto estará disponible en:

```txt
http://127.0.0.1:8000
```

---

# Objetivo del Sistema

El sistema LMS permitirá a Systematic mejorar la administración académica mediante una plataforma centralizada, moderna y escalable, adaptándose al crecimiento de estudiantes, cursos y docentes.

---

# Autores

- Leonardo Alexander Palacios Gonzales
- Fabián Alonzo Salas Vásquez
- Carlos David Apolaya Mendoza

---
