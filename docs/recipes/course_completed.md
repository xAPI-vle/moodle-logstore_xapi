# Course Completed Recipe
This file documents the "course_completed" recipe.

- [Moodle to xAPI mapping](../../classes/xapi/service.php#L8)
- [xAPI Recipe](../../classes/xapi/recipes/course_completed.php)
- [xAPI Recipe Tests](../../Tests/Xapi/Recipes/CourseCompletedTest.php)

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
        "id": "http://activitystrea.ms/schema/1.0/complete",
        "display": {
            "en-GB": "completed",
            "en-US": "completed"
        }
    },
    "object": {
        "objectType": "Activity",
        "id": "http://www.sample.com/mod/scorm/view.php?id=300",
        "definition": {
            "type": "http://adlnet.gov/expapi/activities/course",
            "name": {
                "en-GB": "Test",
                "en-US": "Test"
            }
        }
    }
}
```
