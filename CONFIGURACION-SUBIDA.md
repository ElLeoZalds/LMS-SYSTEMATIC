# Configuración de Subida de Archivos

## Límites actuales de PHP
- upload_max_filesize: 2M (NECESITA AJUSTE)
- post_max_size: 8M (NECESITA AJUSTE)

## Límites recomendados para producción
Editar php.ini:
```ini
upload_max_filesize = 20M
post_max_size = 25M
max_execution_time = 300
memory_limit = 256M
```

## Cómo encontrar php.ini
Ejecutar:
```bash
php --ini | grep "Loaded Configuration File"
```

## Reiniciar servicios después de cambios
- Apache: sudo systemctl restart apache2
- Nginx + PHP-FPM: sudo systemctl restart php8.2-fpm && sudo systemctl restart nginx
- Laravel Serve: Detener y reiniciar el servidor

## Puntos de subida en el sistema
1. Contenidos del profesor (TeacherController@storeContent)
2. Banner de capacitaciones (TeacherController)
3. Anuncios con adjuntos (TeacherController)
4. Tareas del profesor (TaskController)
5. Entregas de tareas (Student/CourseController)
6. Imágenes en preguntas (AssessmentController)

## Validaciones aplicadas# 1. Encontrar qué php.ini está usando
php --ini | grep "Loaded Configuration File"

# 2. Editar php.ini (reemplaza la ruta con la que te mostró el comando anterior)
sudo nano /etc/php/8.3/cli/php.ini

# 3. Busca y cambia estos valores (Ctrl+W para buscar):
upload_max_filesize = 20M
post_max_size = 25M
max_execution_time = 300
memory_limit = 256M

# 4. Guarda (Ctrl+O, Enter) y sal (Ctrl+X)

# 5. Reinicia PHP (si usas php-fpm)
sudo systemctl restart php8.3-fpm

# 6. Si usas Laravel Serve, reinícialo también
# Detén el servidor (Ctrl+C) y vuelve a iniciar:
php artisan serve
- Tamaño máximo: 20MB
- Extensiones permitidas: PDF, DOC, DOCX, TXT, PPT, PPTX, JPG, PNG, ZIP
- Almacenamiento: storage/app/public
- Acceso público: public/storage (enlace simbólico)
