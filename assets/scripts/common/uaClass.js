/**
 *  <html></html>タグに PC・SP 判別クラス付与
 */
import isMobile from 'ismobilejs'

const uaClass = isMobile.phone ? 'is-sp' : 'is-pc'
export default () => $('html').addClass(uaClass)
