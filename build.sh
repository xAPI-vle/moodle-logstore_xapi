#!/usr/bin/env sh

# Creates a folder to zip.
rm -f xapi.zip
php -r "readfile('https://getcomposer.org/installer');" | php
php composer.phar install --no-interaction --no-dev
cp -r . ../moodle_logstore_build

# Removes unused files and folders.
find ../moodle_logstore_build -type d -name 'tests' | xargs rm -rf
find ../moodle_logstore_build -type d -name 'docs' | xargs rm -rf
find ../moodle_logstore_build -type d -name '.git' | xargs rm -rf
find ../moodle_logstore_build -type f -name 'composer.*' | xargs rm -rf
find ../moodle_logstore_build -type f -name 'phpunit.*' | xargs rm -rf
find ../moodle_logstore_build -type f -name '*.md' | xargs rm -rf

# Creates the zip file.
mv ../moodle_logstore_build xapi
zip -r xapi.zip xapi -x "xapi/.git/**/*"
rm -rf xapi

# Updates Github.
git add xapi.zip
git commit -m "Builds zip file."
git push
