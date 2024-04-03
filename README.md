# [Moodle Logstore xAPI](https://moodle.org/plugins/view/logstore_xapi)
> Emits [xAPI](https://github.com/adlnet/xAPI-Spec/blob/master/xAPI.md) statements using the [Moodle](https://moodle.org/) Logstore.

- Install the plugin using [our zip installation guide](/docs/install-with-zip.md).
- Process events before the plugin was installed using [our historical events guide](/docs/historical-events.md).
- Ask questions via the [Github issue tracker](https://github.com/xAPI-vle/moodle-logstore_xapi/issues).
- Report bugs and suggest features with the [Github issue tracker](https://github.com/xAPI-vle/moodle-logstore_xapi/issues).
- View the supported events in [our `get_event_function_map` function](/src/transformer/get_event_function_map.php).
- Change existing statements for the supported events using [our change statements guide](/docs/change-statements.md).
- Create statements using [our new statements guide](/docs/new-statements.md).


## Yet Instructions
These instructions are a guide to make a working moodle logstore dev environment. It is convoluted, but I am trying to document everything clearly here.

### Cloning Repos
So you need three repos. Unfortunately unless we figure a way around it you need to embed one of the repos into another. I tried symlinks but Moodle explicitly doesn't support symlinks for plugins because they tend to do relative path calls :/

#### Clone Moodle Source
This is just getting a copy of core moodle source code

`git clone https://github.com/moodle/moodle.git [Moodle Src Dir]`

#### Clone Logstore Source
What we are doing here is cloning THIS repo into the moodle src at a special location.

Navigate to `[Moodle Src Dir]/admin/tool/log/store`. We are going to clone this repo into a new `xapi` dir within that location. 

```
cd [Moodle Src Dir]/admin/tool/log/store
git clone git@github.com:yetanalytics/moodle-logstore.git xapi
```

#### Clone Docker Support
Now we are going to clone another repo, a specialized Yet version of the `moodlehq/moodle-docker` docker support repo.

`git clone git@github.com:yetanalytics/moodle-docker.git [Docker Support Dir]`

### Starting Up
Now that we have all the stuff, we can start setting things up. 

#### 1. Config and Container Start

First you need to run some commands to init the environment. From the root of your `moodle-docker` repo, run:

```bash
# ENV
export MOODLE_DOCKER_WWWROOT=[Moodle Src Dir]
export MOODLE_DOCKER_DB=pgsql
cp config.docker-template.php $MOODLE_DOCKER_WWWROOT/config.php

# Start containers
bin/moodle-docker-compose up -d

# Wait for DB to come up. This does nothing but will just run until Moodle is ready then terminate itself letting you know you are good.
bin/moodle-docker-wait-for-db
```

This starts all the associated services including the LRS. When you eventually want to shut these down, it's just:

`bin/moodle-docker-compose down`

*NOTE:* So far I haven't really figured out persisting this stuff, would be a good next step. As it stands you need to do everything below this point again every time you do a container restart.

#### 2. Initialize Moodle

I recommend to skip web-based "installation wizard" or whatever by running a command to basically preinstall moodle in a generic testing configuration.

`bin/moodle-docker-compose exec webserver php admin/cli/install_database.php --agree-license --fullname="Docker moodle" --shortname="docker_moodle" --summary="Docker moodle site" --adminpass="test" --adminemail="admin@example.com"`

This will setup a single admin account with the following credentials:

Username: `admin`

Password: `test`

You should now be able to login at `localhost:8000` with the above creds.

### Configure Logstore

Now we need to do a few things in moodle to get the xAPI Logstore running. 

#### Turn on Logstore
- Log In as `admin`
- Navigate to `Site Administration` at the top
- Go to `Plugins`
- Go down to `Logging` subsection
- Click `Manage Log Stores`
- On this page, click the eye icon to Enable `Logstore xAPI`
- After it's enabled, click `Settings` on that row
- Configure the following settings and click Save:
![](xapi-config.png)

*Note: * I disabled batching in above config so that its real-time. This is BAD for real deployments but helpful for us. Also you'll note the LRS hostname is the alias from the docker-compose in our custom moodle-docker config. It should work as-is.

### Using Logstore

You should now have a working plugin. Any events that are instrumented should be sent to the SQL LRS install. You can get there via localhost:8080. 

Easiest test is just create and view a course as the admin user. That should send statements.
