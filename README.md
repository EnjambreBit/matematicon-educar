Matematicon
======================

Plataforma para que los chicos de primaria aprendan sobre las figuras geometricas utilizandolas para crear dibujos artisticos.

Instalación
======================

* ln -s crafting-tool server/web/

* cd server

* curl -sS https://getcomposer.org/installer | php

* php composer.phar install

* cp app/config/parameters.yml.dist app/config/parameters.yml

* editar app/config/parameters.yml

* ./app/console doctrine:schema:create

* Cargar la tabla scene (todo: pasarlo a fixture)

matematicon=# SELECT * FROM scene;
 id |   title   
 ----+-----------
   1 |  Acuático
   2 | Rural
   3 | Urbano
