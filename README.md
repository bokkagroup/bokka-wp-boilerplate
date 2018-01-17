# Catalyst WP Boilerplate
Boilerplate designed to get you up and running with Catalyst WP in a few short minutes.

**Note:** The best way to utilize this package is via the [Catalyst WP CLI](https://github.com/bokkagroup/catalyst-wp-cli) as it will automatically generate necessary configuration files.

### Features
* Composer Support
* Wordpress Loaded from `/wp` directory
* `.gitignore` setup

## Getting Started

1. Make sure you have [Composer](https://getcomposer.org/) installed
2. Clone this repo and `cd` into it via terminal
3. Run `composer install`
4. Add database credentials and nonces to `wp-config.php` (see gotchas below)
5. Duplicate the Atom theme and name the copied directory `atom-child`
6. Update the child theme's `style.css` header so that `Theme Name:` is `Atom Child` and add a field for `Template: atom`
7. Profit

For more details on working with the Catalyst WP Toolbox see documentation for the [Atom Theme](https://github.com/bokkagroup/atom)

### Gotchas
* [Catalyst WP CLI](https://github.com/bokkagroup/catalyst-wp-cli) will generate a `wp-config.php` file for you. If you're not using the CLI you will need to manually create one in the project root - do not put in the `/wp` directory. You will also need to add the following lines to the file:
    * Autoload Composer dependencies
        ```
        require __DIR__ . '/vendor/autoload.php';
        ```
    * Load dependencies from the `/wp` and `/wp-content` directories
        ```
        define('WP_CONTENT_DIR', dirname( __FILE__ ) . '/wp-content');
        define('WP_CONTENT_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/wp-content');
        define('WP_SITEURL', 'http://' . $_SERVER['SERVER_NAME'] . '/wp');
        define('WP_HOME', 'http://' . $_SERVER['SERVER_NAME']);
        ```

* Certain out of the box wordpress hosts may not let you have control over the `index.php` file which is required to load the composer version of wordpress. As long as you are using WP v4+ you should be fine

* Some hosts may also not allow you to modify `wp-config.php` if this is the case you need to find somewhere you can add the composer autoload script. Or find a new host.

**Note:** Please submit any issues you are having to the [Atom Theme](https://github.com/bokkagroup/atom)