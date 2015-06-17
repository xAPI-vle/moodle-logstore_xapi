## Installation and Upgrading
You'll need to install [Composer](https://getcomposer.org/) first.

- Install with `composer require learninglocker/moodle-xapi-translator`.
- Update with `composer update learninglocker/moodle-xapi-translator`.


## Supported Events
Mapping for moodle event names to translator event classes can be found in the [Controller](../src/Controller.php).

Moodle Event | Translator Event | Test
--- | --- | ---
\core\event\course_viewed | [CourseViewed](../src/Events/CourseViewed.php) | [CourseViewedTest](../tests/CourseViewedTest.php)
\mod_page\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php)
\mod_quiz\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php)
\mod_url\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php)
\mod_folder\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php)
\mod_book\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php)
\mod_quiz\event\attempt_preview_started | [AttemptStarted](../src/Events/AttemptStarted.php) | [AttemptStartedTest](../tests/AttemptStartedTest.php)
\mod_quiz\event\attempt_reviewed | [AttemptReviewed](../src/Events/AttemptReviewed.php) | [AttemptReviewedTest](../tests/AttemptReviewedTest.php)
\core\event\user_loggedin | [UserLoggedin](../src/Events/UserLoggedin.php) | [UserLoggedinTest](../tests/UserLoggedinTest.php)
\core\event\user_loggedout | [UserLoggedout](../src/Events/UserLoggedout.php) | [UserLoggedoutTest](../tests/UserLoggedoutTest.php)
\mod_assign\event\submission_graded | [AssignmentGraded](../src/Events/AssignmentGraded.php) | [AssignmentGradedTest](../tests/AssignmentGradedTest.php)
\mod_assign\event\assessable_submitted | [AssignmentSubmitted](../src/Events/AssignmentSubmitted.php) | [AssignmentSubmittedTest](../tests/AssignmentSubmittedTest.php)
