# User Logged In Recipe
This file documents the "user_loggedin" recipe.

- [Moodle to xAPI mapping](../../classes/xapi/service.php#L14)
- [xAPI Recipe](../../classes/xapi/recipes/user_loggedin.php)
- [xAPI Recipe Tests](../../Tests/Xapi/Recipes/UserLoggedinTest.php)

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
        "id": "https://brindlewaye.com/xAPITerms/verbs/loggedin/",
        "display": {
            "en-GB": "logged in",
            "en-US": "logged in"
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
