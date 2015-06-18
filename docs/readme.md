- [Installation](installation.md)
- [Supported Events](#supported-events)
- [Plugin Design](#plugin-design)
- [Plugin Release Process](#plugin-release-process)

## Supported Events
Moodle Event Name | xAPI Recipe Example
--- | ---
\core\event\course_viewed | [CourseViewed](https://github.com/LearningLocker/xAPI-Recipe-Emitter/blob/master/docs/examples/CourseViewed.json)
\mod_page\event\course_module_viewed | [ModuleViewed](https://github.com/LearningLocker/xAPI-Recipe-Emitter/blob/master/docs/examples/ModuleViewed.json)
\mod_quiz\event\course_module_viewed | [ModuleViewed](https://github.com/LearningLocker/xAPI-Recipe-Emitter/blob/master/docs/examples/ModuleViewed.json)
\mod_url\event\course_module_viewed | [ModuleViewed](https://github.com/LearningLocker/xAPI-Recipe-Emitter/blob/master/docs/examples/ModuleViewed.json)
\mod_folder\event\course_module_viewed | [ModuleViewed](https://github.com/LearningLocker/xAPI-Recipe-Emitter/blob/master/docs/examples/ModuleViewed.json)
\mod_book\event\course_module_viewed |
[ModuleViewed](https://github.com/LearningLocker/xAPI-Recipe-Emitter/blob/master/docs/examples/ModuleViewed.json)
\mod_quiz\event\attempt_preview_started | [ModuleViewed](https://github.com/LearningLocker/xAPI-Recipe-Emitter/blob/master/docs/examples/ModuleViewed.json)
\mod_quiz\event\attempt_reviewed | [ModuleViewed](https://github.com/LearningLocker/xAPI-Recipe-Emitter/blob/master/docs/examples/ModuleViewed.json)
\mod_scorm\event\course_module_viewed | [ModuleViewed](https://github.com/LearningLocker/xAPI-Recipe-Emitter/blob/master/docs/examples/ModuleViewed.json)
\core\event\user_loggedin | [UserLoggedin](https://github.com/LearningLocker/xAPI-Recipe-Emitter/blob/master/docs/examples/UserLoggedin.json)
\core\event\user_loggedout | [UserLoggedout](https://github.com/LearningLocker/xAPI-Recipe-Emitter/blob/master/docs/examples/UserLoggedout.json)
\mod_assign\event\submission_graded | [AssignmentGraded](https://github.com/LearningLocker/xAPI-Recipe-Emitter/blob/master/docs/examples/AssignmentGraded.json)
\mod_assign\event\assessable_submitted  | [AssignmentSubmitted](https://github.com/LearningLocker/xAPI-Recipe-Emitter/blob/master/docs/examples/AssignmentSubmitted.json)

## Plugin Design
The plugin [controls and configures](../classes/log/store.php) the three parts listed below.

- [Moodle Log Expander](https://github.com/LearningLocker/Moodle-Log-Expander)
- [Moodle to xAPI Translator](https://github.com/LearningLocker/Moodle-xAPI-Translator)
- [xAPI Recipe Emitter](https://github.com/LearningLocker/xAPI-Recipe-Emitter)

The plugin uses the three parts listed above in the following way.

1. The plugin passes raw event data from the logstore_standard_log to the Expander.
2. The Expander expands events with data from your Moodle Database.
3. The plugin passes the expanded events from step 2 to the Translator.
4. The Translator translates expanded events to xAPI recipe options.
5. The plugin passes the translated events from step 4 to the Emitter.
6. The Emitter constructs the translated events as xAPI statements and emits them to the [configured LRS](installation.md#configuration).

These parts have been separated into their own repositories to improve their reusability since these parts will be used in other projects.

## Plugin Release Process
This process has been documented for collaborators (users that have write access to the repository) who are releasing new versions of this plugin.

1. Run `composer build` on the branch to be released.
2. Commit changes from step 1.
3. Create release on Github.
  1. Document patches.
  2. Document minor changes.
  3. Document major changes.
  4. Document migrations and additional notes.
