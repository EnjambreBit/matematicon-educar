Matematicon
======================

Plataforma para que los chicos de primaria aprendan sobre las figuras geometricas utilizandolas para crear dibujos artisticos.

Instalación
======================


* cd server

* curl -sS https://getcomposer.org/installer | php

* php composer.phar install

* ./app/console doctrine:database:create

* ./app/console doctrine:schema:create

* Cargar la tabla scene (todo: pasarlo a fixture)

matematicon=# SELECT * FROM scene;
 id |   title   
 ----+-----------
   1 |  Acuático
   2 | Rural
   3 | Urbano


* cd web

* ln -s ../../crafting-tool

* Publicar el proyecto en apache2

* Acceder por http a http://.../server/web/crafting-tool/main.html o http://..../crafting-tool/main.html dependiendo de si se publico todo el proyecto o solo la carpeta web.
