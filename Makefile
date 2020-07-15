ARTIFACT_FILES=$$(paste --delimiters ' ' --serial artifacts.include)
ARTIFACT_NAME="UberGallery-$$(git describe --tags --exact-match HEAD 2> /dev/null || git rev-parse --short HEAD)"

dev development: # Build application for development
	@composer install --no-interaction
	@npm install && npm run dev

prod production: # Build application for production
	@composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
	@npm install --no-save && npm run production && npm prune --production

update upgrade: # Update application dependencies
	@composer update && npm update && npm install && npm audit fix

test: # Run coding standards/static analysis checks and tests
	@app/vendor/bin/php-cs-fixer fix --diff --dry-run \
		&& app/vendor/bin/psalm \
		&& app/vendor/bin/phpunit --coverage-text

coverage: # Generate an HTML coverage report
	@app/vendor/bin/phpunit --coverage-html .coverage

tunnel: # Expose the application via secure tunnel
	@ngrok http -host-header=rewrite http://ubergallery.local:80

clear-assets: # Clear the compiled assets
	@rm app/assets/* -rfv

clear-cache: # Clear the application cache
	@rm cache/* -rfv

tar: # Generate tarball
	@tar --exclude-vcs --exclude=cache/* --exclude=app/resources \
		--create --gzip --file artifacts/$(ARTIFACT_NAME).tar.gz $(ARTIFACT_FILES)

zip: # Generate zip file
	@zip --quiet --exclude "*.git*" "cache/**" "app/resources/*" \
		--recurse-paths artifacts/$(ARTIFACT_NAME).zip $(ARTIFACT_FILES)

artifacts: clear-assets production tar zip # Generate release artifacts
