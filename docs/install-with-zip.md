# Installing with the ZIP file
This page contains a guide for users wanting to install the plugin. You obviously need to have [Moodle](https://moodle.org/) installed first and you need to be logged in as an Admin.

1. Download the "xapi.zip" file from [the latest release](https://github.com/xAPI-vle/moodle-logstore_xapi/releases/latest).
1. Go to "http://your.moodle/admin/tool/installaddon/index.php".
1. Drag and drop your download from step 1.
1. Click "Install plugin from the ZIP file".
1. Click “Install plugin!”.
1. Click “Upgrade Moodle database now”.
1. Click “Continue”.
1. Set your “endpoint” to your LRS endpoint (e.g. “<http://your.lrs/xAPI>”).
1. Set your “username” to your LRS basic auth key/username (e.g. “d416e6220812740d3922eb09813ebb4163e8eb3e”).
1. Set your “password” to your LRS basic auth secret/password (e.g. “bc7e0a2edd5d1969b6d774e679d4eb4e7a35be13”).
1. Click “Save changes”.
1. Go to "http://your.moodle/admin/settings.php?section=managelogging".
1. Enable the "Logstore xAPI" plugin.

### Notes
- If zip installation is disabled, you can unzip the zip file to moodle/admin/tool/log/store instead of steps 3 to 5 above.
- The settings you configured in steps 8, 9, and 10 can be changed by navigating to "http://your.moodle/admin/settings.php?section=logsettingxapi".
- If you're having some issues with this guide, please create a new issue on [our Github issue tracker](https://github.com/xAPI-vle/moodle-logstore_xapi/issues). 
