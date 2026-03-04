# Guía de despliegue en VPS

Esta guía describe el flujo de despliegue automatizado hacia un Servidor Privado Virtual (VPS) mediante **GitHub Actions**. El objetivo es que los desarrolladores solo se preocupen de hacer *push* a la rama principal, mientras GitHub se encarga de compilar y transferir el código, incluyendo los `node_modules` y la compilación SSR de Inertia.

---

## Estrategia de compilación remota

Tradicionalmente, en servidores compartidos, Node.js no está instalado o compilar los módulos ocupa demasiada memoria RAM.
Nuestra estrategia traslada esa carga intensiva a los servidores de GitHub Actions:

1. **GitHub Runner** se encarga de descargar `node_modules` y ejecutar `npm run build`.
2. Una vez que se genera el empaquetado final de Vue y el SSR, **se transfiere todo el proyecto ya compilado** al VPS usando `rsync` a través de SSH.
3. El VPS simplemente recibe los directorios `public/build`, `bootstrap/ssr`, la carpeta `vendor` e incluso la carpeta `node_modules`. Así ahorramos recursos del servidor y eliminamos caídas durante los procesos de construcción.

---

## Configuración de GitHub (secrets)

Para que el flujo funcione y pueda copiar los archivos de forma segura a nuestro servidor, **debes configurar las siguientes variables de entorno (Secrets)** en el menú web de tu repositorio en GitHub (*Settings > Secrets and variables > Actions*):

- `SERVER_HOST`: La dirección IP o dominio de nuestro VPS (ej. `192.168.1.50`).
- `SERVER_USER`: El usuario SSH con el que iniciamos sesión (ej. `deployer` o `ubuntu`).
- `SSH_PRIVATE_KEY`: La clave privada SSH (que empieza por `-----BEGIN OPENSSH PRIVATE KEY-----`) del usuario que tiene permisos para acceder y modificar las carpetas.
- `DEPLOY_PATH`: La ruta absoluta dentro del servidor VPS donde se aloja el proyecto (ej. `/var/www/termosalud`).

---

## El flujo paso a paso

Si revisas el archivo `.github/workflows/deploy.yml`, el flujo realiza de forma secuencial:

1. Clonar el repositorio.
2. Preparar el entorno con las versiones correctas de PHP y Node.js.
3. Instalar dependencias de servidor (`composer install --no-dev`) sin requerir interacción manual.
4. Instalar las dependencias de cliente (`npm ci`).
5. Transpilar frontend mediante Vite e Inertia (`npm run build`).
6. Enviar por `rsync` el contenido asegurando no pisar ni borrar el archivo `.env` del servidor real ni vaciar las carpetas puramente locales (vistas, cachés de framework, etc.).
7. Por último interviene por comandos remotos ordenando a Laravel que refresque su caché, aplique migraciones (`migrate --force`) y reinicie las colas de trabajos en segundo plano (`queue:restart`).

### Manejo de SSR e Inertia

Como esta es una SPA tratada con **Server-Side Rendering** a través de Inertia para potenciar el SEO global, el servidor cuenta con un pequeño puerto Node constante interceptando parte del ciclo de vida. Dicho proceso suele estar supervisado bajo una herramienta PM2 o Supervisor en el VPS Linux.

En la parte inferior del flujo desplegable se encuentra una referencia comentada:
`pm2 restart ssr-termosalud || true`
Al descomentarla y adaptarla al nombre del proceso de PM2 en el VPS real, el microservicio node SSR se reiniciará inmediatamente tomando los nuevos cambios aplicados sin necesidad del administrador.

---

## Notas de mantenimiento

Recuerda que estas operaciones en servidor deben tener los permisos controlados: la carpeta dentro del `DEPLOY_PATH` debe pertenecer mayoritariamente al grupo asociado al servidor local como pueda ser `www-data` a fin de que los ficheros que lleguen del Action modifiquens y funcionen correctamente para Apache / Nginx sin levantar alertas de protección o errores fatales "500".
