# TI-wp-content-theme
Wordpress theme for [content.tunapanda.org](http://content.tunapanda.org/).

## How's it going?

* [Issue tracking](https://waffle.io/tunapanda/TI-wp-content-theme/)
* [Metrics](https://www.dasheroo.com/reports/48fa1964f67d528a166fa6bc976f897d/public)

## Plugins

We are using these plugins from the wordpress plugin repositories

* H5P (to install/update h5p content types follow the instructions [here](https://h5p.org/update-all-content-types))
* wp-h5p-xapi
* tabby-responsive-tabs
* profile-builder
* logged-in-user-shortcode
* wpMandrill (mail server, optional)
 
We are also using [github updater](https://github.com/afragen/github-updater) for some plugins

Clone the github-updater repository and copy the plugin to your wordpress plugins directory. 
Go to Settings > GitHub Updater > Install Plugin and enter the url for the following plugins

* [tabby-cookie](https://github.com/tunapanda/tabby-cookie)
* [wp-remote-sync](https://github.com/tunapanda/wp-remote-sync)
* [Dasheroo KPIs](https://github.com/tunapanda/wp-dasheroo-kpis) (optional)

## Setting up a local environment for hacking

In order to participate in the developemnt, you need to have the following thigs set up on your computer:

 * Wordpress (This implies a webserver, e.g. Apache, and a database server, e.g. MySql).

Then, clone this repository and put it in your Wordpress themes folder. If you prefer, you can clone it to somewhere else on your local machine as symlink it from the themes folder. 

Important: After you clone and install the theme on your machine, you will need to activate it and this is how;
- Go to Apperance, point to theme and click.
 

- <img src="https://raw.githubusercontent.com/tunapanda/TI-wp-content-theme/master/theme_lead.png"> 
-

- Locate content_tunapanda_org theme and click on Activate.


-<img src="https://raw.githubusercontent.com/tunapanda/TI-wp-content-theme/master/activate_lead.png">


Important! Remember to always do:

```
git pull
```

So you have the latest version of the theme on your computer when you deploy it to the live site.

Have fun!
