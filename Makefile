start:
	php artisan serve --host 0.0.0.0

install:
	composer install

migrate:
	php artisan migrate --force

test:
	php artisan test

lint:
	composer exec phpcs

lint-fix:
	composer phpcbf
