# Installation
This file documents how to install this plugin. You obviously need to have [Moodle](https://moodle.org/) installed first.

1. Run `cd admin/tool/log/store/` from the root directory of Moodle.
2. Run `git clone git@github.com:jlowe64/moodle-logstore-xapi.git emitter`.
3. Run `cd emitter && composer install --no-interaction`.
4. Run through the Moodle update script.
5. Go to the admin page and turn on xapi logstore using the little icon to make it visible.
6. Go to the settings page and add the LRS endpoint, username, and password to connect. Hit save.