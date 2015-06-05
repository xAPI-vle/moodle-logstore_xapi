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
\mod_scorm\event\course_module_viewed | [viewed](/recipes/viewed.md)