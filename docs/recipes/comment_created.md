# Comment Created Recipe
This file documents the "comment_created" recipe.

- [Moodle to xAPI mapping](../../classes/xapi/service.php#L10)
- [xAPI Recipe](../../classes/xapi/recipes/comment_created.php)
- [xAPI Recipe Tests](../../Tests/Xapi/Recipes/CommentCreatedTest.php)

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
        "id": "http://adlnet.gov/expapi/verbs/commented",
        "display": {
            "en-GB": "commented",
            "en-US": "commented"
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
