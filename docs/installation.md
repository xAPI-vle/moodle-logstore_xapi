# Installation
This page contains a guide for users wanting to install the plugin. You obviously need to have [Moodle](https://moodle.org/) installed first and you need to be logged in as an Admin.

1. Download the plugin. [Click here to begin download](../xapi.zip?raw=true).
2. Go to "http://www.example.com/admin/tool/installaddon/index.php" (replacing “www.example.com” with your own domain).
3. Drag and drop your download from step 1.
4. Click "Install plugin from the ZIP file".
5. Click “Install plugin!”.
6. Click “Upgrade Moodle database now”.
7. Click “Continue”.
8. Set your “endpoint” to your LRS endpoint (e.g. “<http://demo.learninglocker.net/data/xAPI>”).
9. Set your “username” to your LRS basic auth username (e.g. “d416e6220812740d3922eb09813ebb4163e8eb3e”).
10. Set your “password” to your LRS basic auth password (e.g. “bc7e0a2edd5d1969b6d774e679d4eb4e7a35be13”).
11. Click “Save changes”.
12. Go to "http://www.example.com/admin/settings.php?section=managelogging" (replacing “www.example.com” with your own domain).
13. Enable the "Logstore xAPI" plugin.

*Note that the above example LRS endpoint, username, and password utilise the [demo LRS](http://demo.learninglocker.net/) provided by [Learning Locker](http://learninglocker.net/). If you're utilising the demo LRS, you can login to the website with the email “demouser@learninglocker.net” and the password “demouser”. Once logged in, you can view statements on the ["Statements" page for the "Demo" LRS](http://demo.learninglocker.net/lrs/554a45e98fbdd7cd406c171e/statements).*

* If zip installation is disabled, you can unzip the zip file to moodle/admin/tool/log/store instead of steps 3 to 5 above.*

*The settings you configured in steps 8, 9, and 10 can be changed by navigating to "http://www.example.com/admin/settings.php?section=logsettingxapi" (replacing “www.example.com” with your own domain).*
