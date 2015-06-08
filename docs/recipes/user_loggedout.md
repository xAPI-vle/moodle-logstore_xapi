# User Logged Out Recipe
This file documents the "user_loggedout" recipe.

- [Moodle to xAPI mapping](../../classes/xapi/service.php#L15)
- [xAPI Recipe](../../classes/xapi/recipes/user_loggedout.php)
- [xAPI Recipe Tests](../../Tests/Xapi/Recipes/UserLoggedoutTest.php)

## Example
```
{
    "actor": {
        "objectType": "Agent",
        "name": "Test User",
        "account": {
            "name": "123"
            "homePage": "http://www.example.com/users/profile.php?id=2"
        }
    },
    "verb": {
        "id": "https://brindlewaye.com/xAPITerms/verbs/loggedout/",
        "display": {
            "en-GB": "logged out",
            "en-US": "logged out"
        }
    },
    "object": {
        "objectType": "Activity",
        "id": "http://www.sample.com/mod/scorm/view.php?id=300",
        "definition": {
            "type": "http://lrs.learninglocker.net/define/type/unknown"
        }
    }
}
```
