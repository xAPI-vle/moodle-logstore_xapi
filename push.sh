#!/usr/bin/env sh

git status
git push "https://${GH_TOKEN}@${GH_REF}" ${TRAVIS_BRANCH}:${TRAVIS_BRANCH}
