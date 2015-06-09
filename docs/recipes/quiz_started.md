# Quiz Started Recipe
This file documents the "quiz_started" recipe.

- [Moodle to xAPI mapping](../../classes/xapi/service.php#L11)
- [xAPI Recipe](../../classes/xapi/recipes/quiz_started.php)
- [xAPI Recipe Tests](../../Tests/Xapi/Recipes/QuizStartedTest.php)

## Example
```
{
    "actor": {
        "objectType": "Agent",
        "name": "Test User",
        "account": {
            "name": "123"
            "homePage": "http://www.example.com/users/profile.php?id=123"
        }
    },
    "verb": {
        "id": "http://activitystrea.ms/schema/1.0/start",
        "display": {
            "en-GB": "started",
            "en-US": "started"
        }
    },
    "object": {
        "objectType": "Activity",
        "id": "http://www.sample.com/mod/scorm/view.php?id=3",
        "definition": {
            "type": "http://lrs.learninglocker.net/define/type/unknown",
            "name": {
                "en-GB": "Test",
                "en-US": "Test"
            }
        }
    },
    "context": {
        "contextActivities": {
            "grouping": [{
                "objectType": "Activity",
                "id": "http://www.sample.com/mod/scorm/view.php?id=300",
                "definition": {
                    "type": "http://adlnet.gov/expapi/activities/course",
                    "name": {
                        "en-GB": "Test",
                        "en-US": "Test"
                    }
                }
            }]
        }
    }
}
```
