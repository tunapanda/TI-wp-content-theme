# TI-wp-content-theme
Wordpress theme for [content.tunapanda.org](http://content.tunapanda.org/).

## Hacking

In order to participate in the developemnt, you need to have the following thigs set up on your computer:

 * Wordpress (This implies a webserver, e.g. Apache, and a database server, e.g. MySql).
 * NodeJS and NPM.
 * Grunt (install with `npm install -g grunt grunt-cli`)

Then, clone this repository and put it in your Wordpress themes folder. If you prefer, you can clone it to somewhere else on your local machine as symlink it from the themes folder. Inside the cloned folder, run:

```
npm install
```

In order to install some dependencies required for the deployment script. Each time you want to deploy onto the server, do:

```
export CONTENT_TUNAPANDA_PASSWORD=<the_deployment_password_that_you_need_to_ask_someone_for>
grunt ftpUploadTask
```

Have fun!
