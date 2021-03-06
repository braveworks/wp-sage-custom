'use strict'; // eslint-disable-line

// const path = require('path')
const { default: ImageminPlugin } = require('imagemin-webpack-plugin')
const imageminMozjpeg = require('imagemin-mozjpeg')
const UglifyJsPlugin = require('uglifyjs-webpack-plugin')

const config = require('./config')

module.exports = {
  plugins: [
    new ImageminPlugin({
      // cacheFolder: path.join(config.paths.root, '.cache'),
      optipng: { optimizationLevel: 7 },
      gifsicle: { optimizationLevel: 3 },
      pngquant: { quality: '65-90', speed: 4 },
      svgo: {
        plugins: [
          { removeUnknownsAndDefaults: false },
          { cleanupIDs: false },
          { removeViewBox: false },
        ],
      },
      plugins: [imageminMozjpeg({ quality: 75 })],
      disable: config.enabled.watcher,
    }),
    new UglifyJsPlugin({
      uglifyOptions: {
        ecma: 5,
        compress: {
          warnings: true,
          drop_console: true,
        },
      },
    }),
  ],
}
