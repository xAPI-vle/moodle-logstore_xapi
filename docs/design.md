# Plugin Design
This page documents how the plugin is designed. The plugin [controls and configures](../classes/log/store.php) the three parts listed below.

- [Moodle Log Expander](https://github.com/LearningLocker/Moodle-Log-Expander)
- [Moodle to xAPI Translator](https://github.com/LearningLocker/Moodle-xAPI-Translator)
- [xAPI Recipe Emitter](https://github.com/LearningLocker/xAPI-Recipe-Emitter)

The plugin uses the three parts listed above in the following way.

1. The plugin passes raw event data from the logstore_standard_log to the Expander.
2. The Expander expands events with data from your Moodle Database.
3. The plugin passes the expanded events from step 2 to the Translator.
4. The Translator translates expanded events to xAPI recipe options.
5. The plugin passes the translated events from step 4 to the Emitter.
6. The Emitter constructs the translated events as xAPI statements and emits them to the [configured LRS](installation.md#configuration).

These parts have been separated into their own repositories to improve their reusability since these parts will be used in other projects.

