<h2>Instalación</h2>
1. Ejecutar:
    <pre>git clone https://github.com/augustotajar/meat_challenge.git</pre>
2. Ejecutar:
    <pre>composer install</pre>
3. Crear una base de datos en MySql para el proyecto:
    <pre>CREATE DATABASE IF NOT EXISTS `meat_challenge` /*!40100 DEFAULT CHARACTER SET utf8 */;</pre>

4. Copiar el .env.example para crear el .env
5. Configurar las credenciales de la base de datos en el .env recien creado.
6. Ejecutar las migrations:
    <pre>php artisan migrate:refresh --seed</pre>
7. Inicializar passport:
    <pre>
    php artisan passport:keys
    php artisan passport:client --personal
    </pre>
8. Ejecutar el proyecto usando:
    <pre>php artisan serve</pre>
9. Listo!

<h2>Prueba</h2>
1. Entrar en el servidor usando el explorador y accediendo a http://localhost:8000 o el puerto indicado.
2. Registrar un usuario
3. Crear un token de acceso para el usuario
4. Listo! Ya puedes usar la API en nombre del usuario.