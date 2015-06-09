# Moodle Events
This file documents Moodle events, the data they provide, and which ones are supported.

## Event Data
Key Name | Description | Example Value
--- | --- | ---
eventname | Description | \mod_scorm\event\course_module_viewed
component | Description | mod_scorm
action | Description | viewed
target | Description | course_module
objecttable | Description | scorm
objectid | Description | 141
crud | Description | r
edulevel | Description | 2
contextid | Description | 1565
contextlevel | Description | 70
contextinstanceid | Description | 300
userid | Description | 2
courseid | Description | 68
relateduserid | Description | 
anonymous | Description | 0
other | Description | N;
timecreated | Description | 1433254161
origin | Description | web
ip | Description | 0:0:0:0:0:0:0:1
realuserid | Description | 

## Supported Events
Event Name | Recipe Name
--- | ---
\mod_page\event\course_module_viewed | [module_viewed](recipes/module_viewed.md)
\core\event\course_completed | [course_completed](recipes/course_completed.md)
\core\event\course_module_completion_updated | [module_completed](recipes/module_completed.md)
\mod_data\event\comment_created | [comment_created](recipes/comment_created.md)
\mod_quiz\event\attempt_started | [quiz_started](recipes/quiz_started.md)
\mod_quiz\event\attempt_submitted | [quiz_submitted](recipes/quiz_submitted.md)
\mod_quiz\event\attempt_abandoned | [quiz_abandoned](recipes/quiz_abandoned.md)
\core\event\user_loggedin | [user_loggedin](recipes/user_loggedin.md)
\core\event\user_loggedout | [user_loggedout](recipes/user_loggedout.md)
\mod_assign\event\submission_graded | [submission_grade](recipes/submission_grade.md)