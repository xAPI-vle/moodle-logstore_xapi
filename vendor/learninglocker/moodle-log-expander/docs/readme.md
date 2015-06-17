## Installation and Upgrading
You'll need to install [Composer](https://getcomposer.org/) first.

- Install with `composer require learninglocker/moodle-log-expander`.
- Update with `composer update learninglocker/moodle-log-expander`.


## Supported Events
Mapping for moodle event names to expander event classes can be found in the [Controller](../src/Controller.php).

Moodle Event | Expander Event | Test
--- | --- | ---
\core\event\course_viewed | [Event](../src/Events/Event.php) | [EventTest](../tests/EventTest.php)
\mod_page\event\course_module_viewed | [ModuleEvent](../src/Events/ModuleEvent.php) | [ModuleEventTest](../tests/ModuleEventTest.php)
\mod_quiz\event\course_module_viewed | [ModuleEvent](../src/Events/ModuleEvent.php) | [ModuleEventTest](../tests/ModuleEventTest.php)
\mod_url\event\course_module_viewed | [ModuleEvent](../src/Events/ModuleEvent.php) | [ModuleEventTest](../tests/ModuleEventTest.php)
\mod_folder\event\course_module_viewed | [ModuleEvent](../src/Events/ModuleEvent.php) | [ModuleEventTest](../tests/ModuleEventTest.php)
\mod_book\event\course_module_viewed | [ModuleEvent](../src/Events/ModuleEvent.php) | [ModuleEventTest](../tests/ModuleEventTest.php)
\mod_quiz\event\attempt_preview_started | [AttemptEvent](../src/Events/AttemptEvent.php) | [AttemptEventTest](../tests/AttemptEventTest.php)
\mod_quiz\event\attempt_reviewed | [AttemptEvent](../src/Events/AttemptEvent.php) | [AttemptEventTest](../tests/AttemptEventTest.php)
\core\event\user_loggedin | [Event](../src/Events/Event.php) | [EventTest](../tests/EventTest.php)
\core\event\user_loggedout | [Event](../src/Events/Event.php) | [EventTest](../tests/EventTest.php)
\mod_assign\event\submission_graded | [AssignmentGraded](../src/Events/AssignmentGraded.php) | [AssignmentGradedTest](../tests/AssignmentGradedTest.php)
\mod_assign\event\assessable_submitted | [AssignmentSubmitted](../src/Events/AssignmentSubmitted.php) | [AssignmentSubmittedTest](../tests/AssignmentSubmittedTest.php)
