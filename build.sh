#!/usr/bin/env sh

rm -f xapi.zip
composer install --no-interaction --no-dev
cp -r . ../moodle_logstore_build
mv ../moodle_logstore_build xapi
zip -r xapi.zip xapi -x "xapi/.git/**/*"
rm -rf xapi
git add xapi.zip
git commit -m "Builds zip file."
git push
