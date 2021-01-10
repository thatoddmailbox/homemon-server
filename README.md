# homemon-server
This project contains the server-side code for homemon. It has two purposes: first, it receives reports from a device with [homemon-daemon](https://github.com/thatoddmailbox/homemon-daemon) and stores them in a database for further analysis. Second, it's able to display a web UI from that data, allowing a user to check on the status of their home and see details about the most recent reports.

Note that this can only receive reports from [homemon-daemon](https://github.com/thatoddmailbox/homemon-daemon)'s HTTP transport. If you want to use the UDP transport, you'll need to set up [homemon-receiver](https://github.com/thatoddmailbox/homemon-receiver). (which can write its reports in the same database as this program, meaning you're still able to use this program's UI)

## Setup
Requirements:
* A webserver capable of running PHP
* MySQL
* [roamer](https://github.com/thatoddmailbox/roamer)

1. Download the code in this repository, and put it somewhere in your webserver's web root.
2. Run `roamer setup` and fill in the created `roamer.local.toml` file with your database credentials.
3. Run `roamer upgrade`.
4. Ensure that you've set up your webserver to block requests to TOML files. Otherwise, someone could download your configuration file!
5. Copy `config-example.inc.php` to `config.inc.php`, and modify it to include your database credentials. If you're using CloudFlare or something similar, you might also need to set `IP_FORWARDING_HEADER`. You will set the `REPORT_TOKEN` later, when you set up homemon-daemon.
6. That's it!
