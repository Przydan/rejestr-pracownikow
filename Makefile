.PHONY: init serve test frontend db db-pg-start db-maria-start clean archive

# Zmienna z nazwą projektu zgodną z wymaganiami (przykład dla K06 i projektu 1)
PROJECT_NAME=wsb_2026_K06_1

init:
	composer update && composer install && php artisan key:generate && make db && make frontend

serve:
	php artisan serve --host=0.0.0.0

test:
	php artisan test --compact

frontend:
	npm install && npm run build

db: db-maria-start
	php artisan config:clear
	sleep 10
	php artisan migrate --seed

db-pg-start:
	@docker start $(PROJECT_NAME)_pg > /dev/null 2>&1 || docker run -d --name $(PROJECT_NAME)_pg -e POSTGRES_PASSWORD= -e POSTGRES_HOST_AUTH_METHOD=trust -e POSTGRES_DB=$(PROJECT_NAME) -p 5432:5432 postgres > /dev/null

db-maria-start:
	@docker start $(PROJECT_NAME) > /dev/null 2>&1 || docker run -d --name $(PROJECT_NAME) --env MARIADB_DATABASE=$(PROJECT_NAME) --env MARIADB_ALLOW_EMPTY_ROOT_PASSWORD=1 -p 3306:3306 mariadb:latest > /dev/null

clean:
	php artisan cache:clear || true
	php artisan config:clear || true
	rm -rf vendor node_modules public/build

archive: clean
	zip -r $(PROJECT_NAME).zip . -x "*.git*" "*.idea*" "*wsb_2026_*.zip*"
