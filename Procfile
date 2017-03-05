web: $(composer config bin-dir)/heroku-php-apache2 web/
worker: bin/console rabbitmq:consumer -m 50 send_sms
