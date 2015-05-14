Matematicon
======================

Plataforma para que los chicos de primaria aprendan sobre las figuras geometricas utilizandolas para crear dibujos artisticos.

#Instalación en Produccion

**Descargar el código e instalar librerias**
* git clone http://gitlab.educ.gov.ar/externos/matematicon.git

**Pasar al branch produccion !**
* git checkout produccion

* cd server

* curl -sS https://getcomposer.org/installer | php

* php composer.phar install

**Configuración de parámetros**

* cp app/config/parameters.yml.dist app/config/parameters.yml
* Editar app/config/parameters.yml y modificar la configuración de la base de datos y la API de Educ.ar:

      parameters:
        database_driver: pdo_pgsql
        database_host: 127.0.0.1
        database_port: null
        database_name: matematicon
        database_user: matematicon
        database_password: matematicon
        mailer_transport: smtp
        mailer_host: 127.0.0.1
        mailer_user: null
        mailer_password: null
        locale: es
        secret: ThisTokenIsNotSoSecretChangeIt
        educ_ar_api_config:
            sitio_nombre: Matematicón
            web_service_client_key: ydMLmAx4
            uri_service_api: http://api-interna.educ.ar/
            sitio_id: 31
            ci: 33

** Crear base de datos **

* ./app/console doctrine:database:create
* ./app/console doctrine:schema:create

** Cargar datos básicos **
* psql matematicon < scripts_sql/scenes.sql
* cd web
* ln -s ../../crafting-tool

**Permisos de archivos:**
* chown -R USUARIO_APACHE:GRUPO_APACHE *
* chmod -R 755 app/cache app/logs

**Publicar el proyecto en apache2:**

      <VirtualHost *:80>
        ServerName matematicon.educ.ar
        ServerAdmin webmaster@localhost
        DocumentRoot /PATH_DONDE_SE_DESCARGO_EL_PROYECTO/server/web
        <Directory /PATH_DONDE_SE_DESCARGO_EL_PROYECTO/server/web>
		  Options FollowSymLinks
		  AllowOverride All
        </Directory>
      </VirtualHost>

Asegurarse que mod_rewrite este habilitado

#Generar empaquetados versión offline

Correr el script build_dist_packages.sh
