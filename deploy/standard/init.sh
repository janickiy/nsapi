# Стартовый скрипт для снифера, вызывается из Makefile  (см Readme)

#!/bin/sh

chmod -R 777 $(pwd)/standard/pre-commit.sh
chmod -R 777 $(pwd)/standard/pre-commit

if [ ! -f $(pwd)/standard/../../.git/hooks/pre-commit ]; then
    cp -p $(pwd)/standard/pre-commit $(pwd)/standard/../../.git/hooks/
fi
