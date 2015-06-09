# Submission Graded Recipe
This file documents the "submission_graded" recipe.

- [Moodle to xAPI mapping](../../classes/xapi/service.php#L16)
- [xAPI Recipe](../../classes/xapi/recipes/submission_graded.php)
- [xAPI Recipe Tests](../../Tests/Xapi/Recipes/SubmissionGradedTest.php)

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
        "id": "http://activitystrea.ms/schema/1.0/submit",
        "display": {
            "en-GB": "submitted",
            "en-US": "submitted"
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
