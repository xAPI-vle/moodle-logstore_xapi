git push --force --quiet "https://${GH_TOKEN}@${GH_REF}" ${TRAVIS_BRANCH}:${TRAVIS_BRANCH} > /dev/null 2>&1
