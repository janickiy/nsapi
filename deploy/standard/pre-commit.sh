# Скрипт хука коммитов - при коммите запускается скрипт и проверяет коммит на соответсвие PSR-стандартам

#!/usr/bin/env bash
# PHP CodeSniffer pre-commit hook for git
#
# @author Soenke Ruempler <soenke@ruempler.eu>
# @author Sebastian Kaspari <s.kaspari@googlemail.com>
#
# see the README

PHPCS_BIN=/usr/bin/phpcs
PHPCS_CODING_STANDARD=PEAR
PHPCS_IGNORE=
TMP_STAGING=".tmp_staging"
PHPSTAN_BIN=$(dirname $0)/../../vendor/bin/phpstan
PHPSTAN_CONFIG_FILE=$(dirname $0)/phpstan/phpstan.neon

# parse config
CONFIG_FILE=$(dirname $0)/config
if [ -e $CONFIG_FILE ]; then
    . $CONFIG_FILE
fi

# simple check if code sniffer is set up correctly
if [ ! -x $PHPCS_BIN ]; then
    echo "PHP CodeSniffer bin not found or executable -> $PHPCS_BIN"
    exit 1
fi

# stolen from template file
if git rev-parse --verify HEAD
then
    against=HEAD
else
    # Initial commit: diff against an empty tree object
    against=4b825dc642cb6eb9a060e54bf8d69288fbee4904
fi

# this is the magic:
# retrieve all files in staging area that are added, modified or renamed
# but no deletions etc
FILES=$(git diff-index --name-only --cached --diff-filter=ACMR $against -- )

if [ "$FILES" == "" ]; then
    exit 0
fi

# create temporary copy of staging area
if [ -e $TMP_STAGING ]; then
    rm -rf $TMP_STAGING
fi
mkdir $TMP_STAGING

# match files against whitelist
FILES_TO_CHECK=""
for FILE in $FILES
do
    echo "$FILE" | egrep -q "$PHPCS_FILE_PATTERN"
    RETVAL=$?
    if [ "$RETVAL" -eq "0" ]
    then
        FILES_TO_CHECK="$FILES_TO_CHECK $FILE"
    fi
done

if [ "$FILES_TO_CHECK" == "" ]; then
    exit 0
fi

# execute the code sniffer
if [ "$PHPCS_IGNORE" != "" ]; then
    IGNORE="--ignore=$PHPCS_IGNORE"
else
    IGNORE=""
fi

if [ "$PHPCS_SNIFFS" != "" ]; then
    SNIFFS="--sniffs=$PHPCS_SNIFFS"
else
    SNIFFS=""
fi

if [ "$PHPCS_ENCODING" != "" ]; then
    ENCODING="--encoding=$PHPCS_ENCODING"
else
    ENCODING=""
fi

if [ "$PHPCS_IGNORE_WARNINGS" == "1" ]; then
    IGNORE_WARNINGS="-n"
else
    IGNORE_WARNINGS=""
fi

# Copy contents of staged version of files to temporary staging area
# because we only want the staged version that will be commited and not
# the version in the working directory
STAGED_FILES=""
for FILE in $FILES_TO_CHECK
do
  ID=$(git diff-index --cached $against $FILE | cut -d " " -f4)

  # create staged version of file in temporary staging area with the same
  # path as the original file so that the phpcs ignore filters can be applied
  mkdir -p "$TMP_STAGING/$(dirname $FILE)"
  git cat-file blob $ID > "$TMP_STAGING/$FILE"
  STAGED_FILES="$STAGED_FILES $TMP_STAGING/$FILE"
done

OUTPUT=$($PHPCS_BIN -s $IGNORE_WARNINGS --standard=$PHPCS_CODING_STANDARD $ENCODING $IGNORE $SNIFFS $STAGED_FILES)
RETVAL=$?

# delete temporary copy of staging area
rm -rf $TMP_STAGING

if [ $RETVAL -ne 0 ]; then
    echo "$OUTPUT" | less
fi

if [ $RETVAL -eq 0 ]; then
    OUTPUT=$($PHPSTAN_BIN analyse --no-progress -c $PHPSTAN_CONFIG_FILE)
    RETVAL=$?

    if [ $RETVAL -ne 0 ]; then
      echo "$OUTPUT" | less
    fi
fi

exit $RETVAL
