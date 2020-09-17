vendor/bin/phpcs --standard=phpcs.xml  
vendor/bin/phpstan analyse -c phpstan.neon --error-format=table web/modules/custom web/themes/custom app
node icons.js
npm run build
