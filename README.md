requires php 7.4  
vendor/bin/phpcs --standard=phpcs.xml  
php -d memory_limit=-1 ./vendor/bin/phpstan analyse -c phpstan.neon --error-format=table web/modules/custom web/themes/custom app
node icons.js
npm run build
