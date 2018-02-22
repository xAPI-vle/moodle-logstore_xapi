# Developers
This page contains documentation for developers (people who would like to contribute code to the project).

- [Setup](#setup)
- [Adding events](#adding-events)
- [Release process](#release-process)

## Setup
From the root directory of your Moodle installation run the script below.
```sh
cd admin/tool/log/store; rm -rf xapi; git clone git@github.com:xAPI-vle/moodle-logstore_xapi.git xapi; cd xapi; php -r "readfile('https://getcomposer.org/installer');" | php; rm -rf vendor; php composer.phar install --prefer-source;
```

If you hadn't already installed the plugin, you'll need to login as an admin and navigate to "http://www.example.com/admin/index.php" (replacing “www.example.com” with your own domain). Once there you can follow on from step 6 of the [user installation guide](installation.md).

## Adding Events
Assuming you've followed the [instructions above](#setup), you can modify and test events by updating the code inside the directories below.

- lib/expander
- lib/translator
- lib/emitter

If you've read the [plugin design](design.md) you should understand what each of these directories are responsible for. Each of the parts have their own documentation on their respective Github repositories (linked below).

- [Moodle Log Expander](../lib/expander/docs/readme.md#adding-events)
- [Moodle to xAPI Translator](../lib/translator/docs/readme.md#adding-events)
- [xAPI Recipe Emitter](../lib/emitter/docs/readme.md#adding-events)

## PHPUnit
Run these with the [Moodle PHPUnit test framework](https://docs.moodle.org/dev/PHPUnit):

```
vendor/bin/phpunit --testsuite logstore_xapi_testsuite
```

### Test Filter
All PHPUnit tests should pass, but if you'd like to only run the tests for specific events, add the `--filter` option:

```
vendor/bin/phpunit --testsuite logstore_xapi_testsuite --filter <Test_Name>
```

e.g.:
```
vendor/bin/phpunit --testsuite logstore_xapi_testsuite --filter course_completed_test
```

## Release Process
This process has been documented for collaborators (users that have write access to the repository) who are releasing new versions of this plugin.

1. Modify the version.php file.
1. Commit and push the changes made.
1. Run `sh build.sh` on the branch to be released.
1. Create release on Github.
  1. Document patches.
  1. Document minor changes.
  1. Document major changes.
  1. Document migrations and additional notes.
