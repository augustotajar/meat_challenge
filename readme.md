<h2>Instalación</h2>
1. Ejecutar:
    <pre>git clone https://github.com/augustotajar/meat_challenge.git</pre>
2. Ejecutar:
    <pre>composer install</pre>
3. Crear una base de datos en MySql para el proyecto:
    <pre>CREATE DATABASE IF NOT EXISTS `meat_challenge`;</pre>
4. Copiar el .env.example para crear el .env
5. Configurar las credenciales de la base de datos en el .env recien creado.
6. Ejecutar las migrations:
    <pre>php artisan migrate</pre>
7. Inicializar passport:
    <pre>
    php artisan passport:keys
    php artisan passport:client --personal
    </pre>
    <p>Pedirá un nombre</p>
8. Ejecutar el proyecto usando:
    <pre>php artisan key:generate</pre>
9. Ejecutar el proyecto usando:
    <pre>php artisan serve</pre>
10. Listo!

<h2>Prueba</h2>
<p>1. Entrar en el servidor usando el explorador y accediendo a http://localhost:8000 o el puerto indicado.</p>
<p>2. Registrar un usuario</p>
<p>3. Crear un token de acceso personal</p>
<p>4. Listo! Ya puedes usar la API en nombre del usuario.</p>