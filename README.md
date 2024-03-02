# SSH Connector

SSH Connector provides a convenient way to connect to your favorite SSH servers listed in a JSON file.

` ⚠️ ` Application under development.

## Usage

Download the binary [here](https://github.com/dansysanalyst/ssh_connector/raw/main/builds/sshconnector) and copy it to your preferred folder (e,g: `~./config/bin/`).

Run `./sshconnector` to access the SSH servers menu.

For your convenience, you can create an alias in your shell configuration. For example, `alias sshs="~./config/bin/sshconnector"`.

### Config Files

Run `sshconnector make:config` to create your `ssh_servers.json` file.

By default, the application looks for configuration files in the app dir (`./ssh_servers.json`) and in the following paths: `~/ssh_servers.json`, `~/.config/ssh_servers.json`.

You may also pass a filepath using the `--file` option.

## Development

Run the app: `php sshconnector`

Run `composer:test` to run all tests.

Run `composer build` to build the app.

## TODO

- Tests

## Tech Stack

- [Laravel Zero](https://laravel-zero.com)
