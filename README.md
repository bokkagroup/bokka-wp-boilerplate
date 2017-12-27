# Catalyst WP Boilerplate
Boilerplate designed to get you up and running with Catalyst WP in a few short minutes.

**Note:** [Catalyst WP CLI](https://github.com/bokkagroup/catalyst-wp-cli) utilizes this package, the best way to use Catalyst is via the CLI.

### Features
* Composer Support
* Wordpress Loaded from /wp directory
* `.gitignore` setup

## Getting Started

1. Make sure you have [Composer](https://getcomposer.org/) installed
2. Clone this repo and `cd` into it via terminal
3. Run `composer install`
4. Add database credentials and nonces
5. Duplicate the Atom theme and name the copied directory `atom-child`
6. Update the child theme's `style.css` so that `Theme Name:` is `Atom Child` and add a field for `Text Domain: atom`
7. Profit

For more details on working with the Catalyst WP Toolbox see documentation for the [Atom Theme](https://github.com/bokkagroup/atom)

### Gotchas
* Certain out of the box wordpress hosts may not let you have control over the `index.php` file which is required to load the composer version of wordpress. As long as you are using WP v4+ you should be fine
* Some hosts may also not allow you to modify `wp-config.php` if this is the case you need to find somewhere you can added the composer autoload script. Or find a new host.

**Note:** Please submit any issues you are having to the [Atom Theme](https://github.com/bokkagroup/atom)