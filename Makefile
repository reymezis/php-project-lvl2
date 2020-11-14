install :
	composer install

lint:
	composer run-script phpcs -- --standard=PSR12 src tests

test:
	php ./vendor/bin/phpunit tests/

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml