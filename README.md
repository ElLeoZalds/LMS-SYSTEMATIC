<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
# LMS Systematic

## About Laravel
Sistema LMS desarrollado para la empresa Systematic con el objetivo de optimizar la gestión académica, centralizar contenidos educativos y mejorar el seguimiento del aprendizaje de los estudiantes.

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:
## Descripción del Proyecto

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).
Este proyecto busca implementar una plataforma LMS (Learning Management System) para la empresa Systematic, una institución dedicada a la capacitación tecnológica y formación profesional.

Laravel is accessible, powerful, and provides tools required for large, robust applications.
El sistema permitirá:

## Learning Laravel
- Centralizar materiales educativos
- Automatizar procesos académicos
- Mejorar el seguimiento del progreso de los estudiantes
- Generar reportes y certificaciones
- Facilitar la gestión de cursos y docentes

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.
## Empresa

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.
**Systematic**  
Empresa dedicada a la capacitación tecnológica y formación profesional en Perú.

## Laravel Sponsors
## Tecnologías Utilizadas

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).
- PHP
- Laravel
- MySQL

### Premium Partners
---

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**
# Requisitos

## Contributing
Antes de ejecutar el proyecto, asegúrate de tener instalado:

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).
- PHP >= 8.2
- Composer
- MySQL
- Laravel
- Node.js y NPM (opcional para frontend)
- Git

## Code of Conduct
---

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).
# Instalación del Proyecto

## Security Vulnerabilities
## 1. Clonar el repositorio

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.
```bash
git clone https://github.com/ElLeoZalds/LMS-SYSTEMATIC.git
```

## License
## 2. Entrar al proyecto

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
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