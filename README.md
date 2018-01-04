`speedlogs-viewer`
==================

This project is here to help to read the log file produced by
https://github.com/ludovicm67/sh-speedlogs.

## Configuration

You can easily edit the configuration editing the [`config.ini`](/config.ini)
file.

The following parameters are required:

  - `path_to_logfile`, wich indicates the path of the log file
    *(default value is `/var/log/ludovicm67_speedlogs.json`)*

  - `max_items`, indicates the maximum number of items to display.
    If it is set to `0`, it will display all possible items.
    *(default value is `20`)*

You can override the `max_items` property by passing it as a `GET` parameter.

## How to run it?

Just go to the directory of the project, and use the `php -S localhost:8080`
command so that PHP can serve it at http://localhost:8080/.

If it works perfectly as desired, you can edit your Nginx or Apache
configuration to serve it if you want.
