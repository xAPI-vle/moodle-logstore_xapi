# Installation
This file documents how to install this plugin. You obviously need to have [Moodle](https://moodle.org/) installed first. You'll also need to install [Git](https://git-scm.com/) for step 2 and [Composer](https://getcomposer.org/) for step 3.

1. Run `cd admin/tool/log/store/` from the root directory of Moodle.
2. Run `git clone git@github.com:jlowe64/moodle-logstore-xapi.git emitter`.
3. Run `cd emitter && composer install --no-interaction`.
4. Go to "http://www.example.com/admin/index.php".
5. Follow through the upgrade and enter your LRS details when required.
6. Go to "http://www.example.com/admin/settings.php?section=managelogging".
7. Enable the "Logstore Emitter" plugin.

*This requires you to be logged in as an Admin.*