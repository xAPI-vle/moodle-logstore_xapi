#!/usr/bin/env sh

composer install --prefer-source --no-interaction --no-dev
zip xapi * -r
git add xapi.zip
git commit -m "Builds zip file."
git push
