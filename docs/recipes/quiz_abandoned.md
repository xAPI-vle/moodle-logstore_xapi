# Quiz Abandoned Recipe
This file documents the "quiz_abandoned" recipe.

- [Moodle to xAPI mapping](../../classes/xapi/service.php#L13)
- [xAPI Recipe](../../classes/xapi/recipes/quiz_abandoned.php)
- [xAPI Recipe Tests](../../Tests/Xapi/Recipes/QuizAbandonedTest.php)

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
        "id": "http://activitystrea.ms/schema/1.0/terminate",
        "display": {
            "en-GB": "terminated",
            "en-US": "terminated"
        }
    },
    "object": {
        "objectType": "Activity",
        "id": "http://www.sample.com/mod/scorm/view.php?id=3",
        "definition": {
            "type": "http://lrs.learninglocker.net/define/type/unknown"
        }
    },
    "context": {
        "contextActivities": {
            "grouping": [{
                "objectType": "Activity",
                "id": "http://www.sample.com/mod/scorm/view.php?id=300",
                "definition": {
                    "type": "http://adlnet.gov/expapi/activities/course"
                }
            }]
        }
    }
}
```
