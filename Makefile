test:
ifndef TRAVIS_PHP_VERSION
	make phpunit
else ifeq ($(TRAVIS_PHP_VERSION), 5.6)
	make phpunit
else
$(info $(TRAVIS_PHP_VERSION))
$(error could not determine PHP version)
endif

phpunit:
	./vendor/bin/phpunit

analyze:
	./vendor/bin/hhvm-wrapper analyze ./src
