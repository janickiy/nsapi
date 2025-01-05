ifdef ${.CURDIR}
	CURRENT_DIR = ${.CURDIR}
else
	CURRENT_DIR = ${CURDIR}
endif

all:
	@echo 'you must enter target: build-prod, build-test or build-dev'

build-prod: install-vendor-prod prod init-project_with_compress upload-777
build-dev: install-vendor dev init-project upload-777

init-project:
	php ${CURRENT_DIR}/install_console.php

init-project_with_compress:
	php ${CURRENT_DIR}/install_console.php --compress="backend"

upload-777:
	chmod -R 777 ${CURRENT_DIR}/../upload

plug-dev:
	php ${CURRENT_DIR}/../vendor/quartz/yii2-utilities/plug-site.php -s "$(plug-status)" -i "$(plug-interval)" -e "$(plug-end)"

plug-prod: plug-dev clear_cache_prod

plug-open:  #открыть сайт из консоли
	php ${CURRENT_DIR}/../vendor/quartz/yii2-utilities/plug-site.php -s "false"

plug-close: #закрыть сайт из консоли по быстрому, время будет указанное ранее
	php ${CURRENT_DIR}/../vendor/quartz/yii2-utilities/plug-site.php -s "true"

clear_cache_prod:
	php ${CURRENT_DIR}/../vendor/quartz/yii2-utilities/cloudflare-api.php -c="clear_cache" --path="${CURRENT_DIR}/../common/config/cloudflare-config.json"

install-vendor-prod:
	composer install --no-dev --optimize-autoloader

install-vendor:
	composer install

prod:
	php ${CURRENT_DIR}/init --env=Production --overwrite=All

dev:
	php ${CURRENT_DIR}/init --env=Development --overwrite=All

test-build: yii-test-migrate yii-test-localization codeception-init codeception-build

test-run: codeception-run

yii-test-migrate:
	php ./../yii_test migrate --migrationPath=@vendor/quartz/yii2-rbac/migrations --interactive=0
	php ./../yii_test migrate --migrationPath=@vendor/quartz/yii2-settings-module/migrations --interactive=0
	php ./../yii_test migrate --migrationPath=@vendor/quartz/yii2-user/migrations --interactive=0
	php ./../yii_test migrate --migrationPath=@vendor/quartz/yii2-two-factor/migrations --interactive=0
	php ./../yii_test migrate --migrationPath=@vendor/quartz/yii2-mailnotify/migrations --interactive=0
	php ./../yii_test migrate --migrationPath=@vendor/kartik-v/yii2-dynagrid/migrations --interactive=0
	php ./../yii_test migrate --migrationPath=@vendor/quartz/yii2-pages/migrations --interactive=0
	php ./../yii_test migrate --migrationPath=@vendor/quartz/yii2-metatag/migrations --interactive=0
	php ./../yii_test migrate --migrationPath=@vendor/quartz/yii2-localization/migrations --interactive=0
	php ./../yii_test migrate --migrationPath=@vendor/quartz/yii2-menu/migrations --interactive=0
	php ./../yii_test migrate --migrationPath=@vendor/quartz/yii2-adminlte-theme/migrations --interactive=0
	php ./../yii_test migrate --migrationPath=@vendor/quartz/yii2-multiple-upload/migrations --interactive=0
	php ./../yii_test migrate --migrationPath=@vendor/quartz/yii2-dashboard/migrations --interactive=0
	php ./../yii_test migrate --migrationPath=@vendor/quartz/yii2-oauth2-server/migrations --interactive=0
	php ./../yii_test migrate --migrationPath=@vendor/quartz/yii2-worker-manager/migrations --interactive=0
	php ./../yii_test migrate --migrationPath=@console/migrations --interactive=0

yii-test-localization:
	php ./../yii_test localization/parse

codeception-init:
	php ./codeception_init.php

codeception-build:
	cd .. && ./vendor/bin/codecept build

codeception-run:
	cd .. && ./vendor/bin/codecept run

init-code-sniffer:
	sh standard/init.sh

first-init:
	sh custom/first_init.sh

phpstan:
	php ${CURRENT_DIR}/../vendor/bin/phpstan analyse -c ${CURRENT_DIR}/standard/phpstan/phpstan.neon