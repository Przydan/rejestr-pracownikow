.PHONY: test frontend serve db-start db
init:
	composer update && composer install && php artisan key:generate && make db && make frontend

serve:
	php artisan serve --host=0.0.0.0

test:
	php artisan test --compact

frontend:
	npm install && npm run build

db: db-start
	php artisan config:clear
	sleep 5
	php artisan migrate --seed

db-start:
	@docker start wsb_2025_k06_p1 > /dev/null 2>&1 || docker run --name wsb_2025_k06_p1 -e POSTGRES_PASSWORD= -e POSTGRES_HOST_AUTH_METHOD=trust -e POSTGRES_DB=wsb_2025_k06_p1 -p 5432:5432 -d postgres > /dev/null
