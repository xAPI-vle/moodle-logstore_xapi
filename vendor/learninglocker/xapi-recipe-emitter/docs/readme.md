## Installation and Upgrading
You'll need to install [Composer](https://getcomposer.org/) first.

- Install with `composer require learninglocker/xapi-recipe-emitter`.
- Update with `composer update learninglocker/xapi-recipe-emitter`.


## Supported Events
Mapping for recipe names to recipe classes can be found in the [Controller](../src/Controller.php).

Recipe Name | Recipe Class | Test
--- | --- | ---
course_viewed | [CourseViewed](../src/Events/CourseViewed.php) | [CourseViewedTest](../tests/CourseViewedTest.php)
module_viewed | [ModuleEvent](../src/Events/ModuleViewed.php) | [ModuleEventTest](../tests/ModuleViewedTest.php)
attempt_started | [AttemptStarted](../src/Events/AttemptStarted.php) | [AttemptStartedTest](../tests/AttemptStartedTest.php)
attempt_completed | [AttemptCompleted](../src/Events/AttemptCompleted.php) | [AttemptCompletedTest](../tests/AttemptCompletedTest.php)
user_loggedin | [UserLoggedin](../src/Events/UserLoggedin.php) | [UserLoggedinTest](../tests/UserLoggedinTest.php)
user_loggedout | [UserLoggedout](../src/Events/UserLoggedout.php) | [UserLoggedoutTest](../tests/UserLoggedoutTest.php)
assignment_graded | [AssignmentGraded](../src/Events/AssignmentGraded.php) | [AssignmentGradedTest](../tests/AssignmentGradedTest.php)
assignment_submitted | [AssignmentSubmitted](../src/Events/AssignmentSubmitted.php) | [AssignmentSubmittedTest](../tests/AssignmentSubmittedTest.php)
