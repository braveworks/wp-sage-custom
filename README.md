# WP Sage Extend Starter Theme

Based on [Sage v8.5.4](https://github.com/roots/sage/blob/8.5.4/README.md#theme-installation) ｜ Webpack assets Builder : Based on [Sage v9.0.1](https://roots.io/sage/docs/theme-development-and-building/)

## Requirements

- [WordPress](https://wordpress.org/) >= 4.7
- [PHP](https://secure.php.net/manual/en/install.php) >= 5.4 (with [`php-mbstring`](https://secure.php.net/manual/en/book.mbstring.php) enabled)
- [Node.js](http://nodejs.org/) >= 6.9.x
- [Yarn](https://yarnpkg.com/en/docs/install)

## Features

- Sass for stylesheets
- Modern JavaScript（ES2015）
- [Webpack](https://webpack.github.io/) for compiling assets, optimizing images, and concatenating and minifying files
- [Browsersync](http://www.browsersync.io/) for synchronized browser testing

## setting

`yarn install` (or `npm i`) and change `assets/config.json`

- `publicPath`: public root path (ex: `/wp-content/themes/{theme_name}`)
- `devUrl`: dev server url
- `proxyUrl`: dev localhost path
- `cacheBusting`: cash busting filename (ex: "[name]\_[hash:8]")

### Build commands

- `yarn start` — Compile assets when file changes are made, start Browsersync session
- `yarn build` — Compile and optimize the files in your assets directory
- `yarn build:production` — Compile assets for production

#### replase publicPath

```sh
SAGE_DIST_PATH=/wp/wp-content/themes/{themename}/dist/ yarn build:production
```
