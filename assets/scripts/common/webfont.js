/**
 * Web Font Loader
 * https://github.com/typekit/webfontloader
 */

import WebFont from 'webfontloader'
// import isMobile from 'ismobilejs'

const fontsCommon = {
  google: {
    families: [
      'Material+Icons',
    ],
  },
  // custom: {
  //   families: ['Noto Sans JP'],
  //   urls: ['https://fonts.googleapis.com/earlyaccess/notosansjp.css'],
  // },
}
// const fonts = isMobile.any ? fontsCommon : Object.assign(fontsCommon, fontsPC)

export default (() => WebFont.load(fontsCommon))()
