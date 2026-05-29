.PHONY: test frontend serve

test:
	php artisan test --compact

frontend:
	npm install && npm run build

serve:
	php artisan serve --host=0.0.0.0
