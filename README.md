*Moodle is copyright [Moodle](http://www.moodle.org)*

## THIS REPO IS UNDER HEAVY DEVELOPMENT AND IS NOT CLOSE TO PRODUCTION READY

## Installation
1. This is a plugin for Moodle, so please install Moodle before beginning.
2. Navigate to /moodle/admin/tool/log/store/
3. Either drop a zipped archive of the plugin (renaming it to xapi) in this folder or pull directly from github using the following command: git clone git@github.com:jlowe64/moodle-logstore-xapi.git xapi
4. Run through the Moodle update script.
5. Go to the admin page and turn on xapi logstore using the little icon to make it visible.
6. Go to the settings page and add the LRS endpoint, username, and password to connect. Hit save.


## Developers
If you wish to help us with developing this Moodle plugin, you may use either the issues area of Github to report issues or create pull requests via your own forks.

## Example of data being passed and statements being sent

Event
```
\mod_scorm\event\course_module_viewed
```

Event info
```
Array
(
    [eventname] => \mod_scorm\event\course_module_viewed
    [component] => mod_scorm
    [action] => viewed
    [target] => course_module
    [objecttable] => scorm
    [objectid] => 141
    [crud] => r
    [edulevel] => 2
    [contextid] => 1565
    [contextlevel] => 70
    [contextinstanceid] => 300
    [userid] => 2
    [courseid] => 68
    [relateduserid] => 
    [anonymous] => 0
    [other] => N;
    [timecreated] => 1433254161
    [origin] => web
    [ip] => 0:0:0:0:0:0:0:1
    [realuserid] => 
)
```

Statement
```
TinCan\Statement Object
(
    [id:protected] => 
    [stored:protected] => 
    [authority:protected] => 
    [version:protected] => 
    [attachments:protected] => Array
        (
        )

    [actor:protected] => TinCan\Agent Object
        (
            [objectType:protected] => Agent
            [name:protected] => 
            [mbox:protected] => mailto:jfowler@charitylearning.org
            [mbox_sha1sum:protected] => 
            [openid:protected] => 
            [account:protected] => TinCan\AgentAccount Object
                (
                    [name:protected] => admin
                    [homePage:protected] => http://localhost/CLCMultisiteMoodle/user/profile.php?id=2
                )

        )

    [verb:protected] => TinCan\Verb Object
        (
            [id:protected] => http://id.tincanapi.com/verb/viewed
            [display:protected] => TinCan\LanguageMap Object
                (
                    [_map:protected] => Array
                        (
                            [en-GB] => viewed
                            [en-US] => viewed
                        )

                )

        )

    [target:protected] => TinCan\Activity Object
        (
            [objectType:TinCan\Activity:private] => Activity
            [id:protected] => http://localhost/CLCMultisiteMoodle/mod/scorm/view.php?id=300
            [definition:protected] => 
        )

    [result:protected] => 
    [context:protected] => 
    [timestamp:protected] => 2015-06-02T16:10:59+02:00
) 
```