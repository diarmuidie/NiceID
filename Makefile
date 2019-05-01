test:
	./vendor/bin/phpunit

lint:
	./vendor/bin/phpcs

clean:
	rm -rf build/ coverage/ vendor/ composer.lock
