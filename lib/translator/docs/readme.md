## Supported Events
Mapping for moodle event names to translator event classes can be found in the [Controller](../src/Controller.php).

Moodle Event | Translator Event | Test | Example
--- | --- | --- | ---
\core\event\course_viewed | [CourseViewed](../src/Events/CourseViewed.php) | [CourseViewedTest](../tests/CourseViewedTest.php) | [CourseViewed](examples/CourseViewed.json)
\mod_page\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_quiz\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_url\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_folder\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_book\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_scorm\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_forum\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_resource\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_quiz\event\attempt_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_choice\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_data\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_feedback\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_lesson\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_lti\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_wiki\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_workshop\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_chat\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_glossary\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_imscp\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_survey\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_url\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_facetoface\event\course_module_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\mod_quiz\event\attempt_preview_started | [AttemptStarted](../src/Events/AttemptStarted.php) | [AttemptStartedTest](../tests/AttemptStartedTest.php) | [AttemptStarted](examples/AttemptStarted.json)
\mod_quiz\event\attempt_reviewed | [AttemptReviewed](../src/Events/AttemptReviewed.php) | [AttemptReviewedTest](../tests/AttemptReviewedTest.php) | [AttemptReviewed](examples/AttemptReviewed.json)
\mod_quiz\event\attempt_reviewed | [QuestionSubmitted](../src/Events/QuestionSubmitted.php) | [AttemptReviewedTest](../tests/QuestionSubmittedTest.php) | [QuestionSubmitted](examples/QuestionSubmitted.json)
\core\event\user_loggedin | [UserLoggedin](../src/Events/UserLoggedin.php) | [UserLoggedinTest](../tests/UserLoggedinTest.php) | [UserLoggedin](examples/UserLoggedin.json)
\core\event\user_loggedout | [UserLoggedout](../src/Events/UserLoggedout.php) | [UserLoggedoutTest](../tests/UserLoggedoutTest.php) | [UserLoggedout](examples/UserLoggedout.json)
\mod_assign\event\submission_graded | [AssignmentGraded](../src/Events/AssignmentGraded.php) | [AssignmentGradedTest](../tests/AssignmentGradedTest.php) | [AssignmentGraded](examples/AssignmentGraded.json)
\mod_assign\event\assessable_submitted | [AssignmentSubmitted](../src/Events/AssignmentSubmitted.php) | [AssignmentSubmittedTest](../tests/AssignmentSubmittedTest.php) | [AssignmentSubmitted](examples/AssignmentSubmitted.json)
\mod_forum\event\discussion_viewed | [DiscussionViewed](../src/Events/DiscussionViewed.php) | [DiscussionViewedTest](../tests/DiscussionViewedTest.php) | [DiscussionViewed](examples/DiscussionViewed.json)
\mod_quiz\event\attempt_abandoned | [AttemptAbandoned](../src/Events/AttemptAbandoned.php) | [AttemptAbandonedTest](../tests/AttemptAbandonedTest.php) | [AttemptAbandoned](examples/AttemptAbandoned.json)
\mod_forum\event\user_report_viewed | [ModuleViewed](../src/Events/ModuleViewed.php) | [ModuleViewedTest](../tests/ModuleViewedTest.php) | [ModuleViewed](examples/ModuleViewed.json)
\core\event\user_created | [UserRegistered](../src/Events/UserRegistered.php) | [UserRegisteredTest](../tests/UserRegisteredTest.php) | [UserRegistered](examples/UserRegistered.json)
\core\event\user_enrolment_created | [EnrolmentCreated](../src/Events/EnrolmentCreated.php) | [EnrolmentCreatedTest](../tests/EnrolmentCreatedTest.php) | [EnrolmentCreated](examples/EnrolmentCreated.json)
\mod_scorm\event\sco_launched | [ScormLaunched](../src/Events/ScormLaunched.php) | [ScormLaunchedTest](../tests/ScormLaunchedTest.php) | [ScormLaunched](examples/ScormLaunched.json)

## Adding Events
1. Create a new ".php" file in the "tests" directory.
2. Change the file from step 1 using the "tests/ModuleEventTest.php" file as an example.
3. Run the tests you've created with "./vendor/bin/phpunit".
4. You should see the tests fail.
5. Create a new ".php" file in the "src/events" directory.
6. Change the file from step 5 using the "src/Events/ModuleEvent.php" file as an example.
7. Run the tests with "./vendor/bin/phpunit".
8. You should see the tests pass if you've done everything correctly.
9. You may wish to repeat steps 2-4 and/or steps 6-8 at this point.
10. Change the "src/Controller.php" file to map the Moodle event to the file you created in step 5.
11. Create a pull request so that everyone else can use your new event.

When adding new events or modifying existing events, we recommend that you follow the [rules of TDD](http://butunclebob.com/ArticleS.UncleBob.TheThreeRulesOfTdd).

1. You are not allowed to write any production code unless it is to make a failing unit test pass.
1. You are not allowed to write any more of a unit test than is sufficient to fail; and compilation failures are failures.
1. You are not allowed to write any more production code than is sufficient to pass the one failing unit test.

### First Pull Request
If this is your first pull request, you need to follow the steps below.

1. Click the "Fork" button (top right of Github). Github will fork (copy) this repository as your own so you can make changes.
2. Run `git remote set-url origin https://github.com/YOUR_USERNAME/moodle-logstore_xapi.git` (replacing `YOUR_USERNAME` for your Github username).

Once this is done, you can then follow the steps for [subsequent pull requests](#subsequent-pull-requests) below.

### Subsequent Pull Requests
1. Run `git add -A; git commit -am "DESCRIPTION";` to commit your changes (replacing `DESCRIPTION` with a description of what you've changed).
2. Run `git push` to push your commits to your forked repository.
3. Go to "https://github.com/YOUR_USERNAME/moodle-logstore_xapi/pulls" (replacing `YOUR_USERNAME` for your Github username).
4. Click the "New pull request" button to begin creating your pull request.
5. Click the "Create pull request" button (twice) to confirm the creation of your pull request.

Once step 5 is complete, we'll test and review your pull request before merging it with the rest of the code.
