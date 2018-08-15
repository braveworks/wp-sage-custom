import Barba from 'barba.js'

const LOCAL_TEST_URL = 'wpsagecutom.test' // 開発用: ローカルWP環境のhost名
const IS_CURRENT_CLASS = 'is-current' // カレントページリンクに付与するクラス

// headタグ内の置換対象
const HEAD_TAG = [
  'meta[name="keywords"]',
  'meta[name="description"]',
  'meta[property^="og"]',
  'meta[name^="twitter"]',
  'meta[itemprop]',
  'link[itemprop]',
  'link[rel="prev"]',
  'link[rel="next"]',
  'link[rel="canonical"]',
  'link[rel="alternate"]',
  'script[type="application/ld+json"]',
].join(',')

const $document = $(document)
const $body = $(document.body)

/**
 * barba.js実行時に諸々処理するユーティリティ
 */
class BarbaUtil {
  href(url) {
    if (url) {
      window.location.href = url
    } else {
      window.location.reload()
    }
  }

  /**
   * head メタ書き換え
   */
  updateHeadMeta(newPageRawHTML) {
    if (Barba.HistoryManager.history.length === 1) {
      return
    }
    const head = document.head
    const newPageRawHead = newPageRawHTML.match(
      /<head[^>]*>([\s\S.]*)<\/head>/i
    )[0]
    const newPageHead = document.createElement('head')
    newPageHead.innerHTML = newPageRawHead

    const oldHeadTags = head.querySelectorAll(HEAD_TAG)
    for (let i = 0; i < oldHeadTags.length; i++) {
      head.removeChild(oldHeadTags[i])
    }
    const newHeadTags = newPageHead.querySelectorAll(HEAD_TAG)
    for (let i = 0; i < newHeadTags.length; i++) {
      head.appendChild(newHeadTags[i])
    }
  }

  /**
   * bodyタグのクラスを更新
   */
  updateBodyClass(newPageRawHTML) {
    const response = newPageRawHTML.replace(
      /(<\/?)body( .+?)?>/gi,
      '$1notbody$2>',
      newPageRawHTML
    )
    const bodyClasses = $(response)
      .filter('notbody')
      .attr('class')
    $body.attr('class', bodyClasses)
  }

  /**
   * 現在のページのリンクにIS_CURRENT_CLASSを付与
   */
  addActiveClass() {
    const $anchor = $document.find('a')
    $anchor.removeClass(IS_CURRENT_CLASS)
    Array.prototype.forEach.call($anchor, element => {
      if ($(element).prop('href') == Barba.HistoryManager.currentStatus().url) {
        $(element).addClass(IS_CURRENT_CLASS)
      }
    })
  }

  /**
   * jQuery matchHeightのリスタート
   */
  resetMatchheight() {
    if ($.fn.matchHeight) {
      $('[data-mh]').matchHeight()
    }
  }

  /**
   * GoogleAnalytics pageview イベント送信
   */
  sendGoogleAnalytics(url) {
    if (window.ga) {
      const sendURL = url
        ? url
        : window.location.pathname.replace(/^\/?/, '/') + window.location.search
      window.ga('send', 'pageview', sendURL)
    }
  }

  /**
   * ContactForm7のjsを再起動
   * other example: jQuery.get(window.location.protocol + window.location.hostname + '/wp-content/plugins/contact-form-7/includes/js/scripts.js');
   */
  resetContactForm7() {
    if (window.wpcf7) {
      const wpcf7Form = document.querySelectorAll('div.wpcf7 > form')
      Array.prototype.forEach.call(wpcf7Form, element => {
        $(element)
          .find('.ajax-loader')
          .remove()
        window.wpcf7.initForm(element)
        if (window.wpcf7.cached) {
          window.wpcf7.refill(element)
        }
      })
    }
  }

  /**
   * BrowserSync dev only : localhost Proxy URL Fix
   * https://github.com/luruke/barba.js/issues/116
   */
  localProxyFix(wrapper, container) {
    const hostname = window.location.host
    if (hostname.match(/localhost:/)) {
      const $wrapper = $(`#${wrapper}`)
      $wrapper
        .find(`.${container}`)
        .html((i, html) =>
          html.replace(new RegExp(LOCAL_TEST_URL, 'g'), hostname)
        )
    }
  }
}
export default BarbaUtil
