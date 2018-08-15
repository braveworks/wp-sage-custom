/**
 * pjax Control
 *
 * using Barba.js
 * http://barbajs.org/
 */

import Barba from 'barba.js'
import { TweenLite } from 'gsap'
import 'gsap/src/uncompressed/plugins/ScrollToPlugin'

import BarbaUtil from 'scripts/util/BarbaUtil'

const WRAPPER_ID = 'wrap' // barba-wrapperクラス
const CONTAINER_CLASS = 'content' // barba-containerクラス
const TIMEOUT = 10000 // pjax失敗時のタイムアウト時間

// 除外リンク
const IGNORE_LINK = [
  '#wpadminbar a',
  'a[href*=wp-admin]',
  'a[target="_blank"]',
  'input',
].join(',')

/**
 * 遷移アニメーション定義
 * 参考: http://barbajs.org/transition.html
 */
const Transiton = {
  start() {
    Promise.all([this.newContainerLoading, this.fadeOut()]).then(
      this.fadeIn.bind(this)
    )
  },
  fadeOut() {
    const d = Barba.Utils.deferred()
    TweenLite.to(this.oldContainer, 0.4, {
      autoAlpha: 0,
      onComplete: () => d.resolve(),
    })
    return d.promise
  },
  fadeIn() {
    TweenLite.set(window, { scrollTo: 0 })
    TweenLite.set(this.newContainer, {
      autoAlpha: 0,
      position: 'absolute',
      top: 0,
      left: 0,
    })
    TweenLite.to(this.newContainer, 1, {
      autoAlpha: 1,
      onComplete: () => {
        TweenLite.set(this.newContainer, { cleaProps: 'all' })
        this.done()
      },
    })
  },
}

/**
 * Barba.js 実行
 */
const pjax = () => {
  const util = new BarbaUtil()

  // ラッパーIDとコンテナクラスを変更
  Barba.Pjax.Dom.wrapperId = WRAPPER_ID
  Barba.Pjax.Dom.containerClass = CONTAINER_CLASS

  // トランジション
  Barba.Pjax.getTransition = () => Barba.BaseTransition.extend(Transiton)

  // リンクチェック
  Barba.Pjax.originalPreventCheck = Barba.Pjax.preventCheck
  Barba.Pjax.preventCheck = (event, el) => {
    if (
      Barba.Pjax.transitionProgress ||
      Barba.HistoryManager.currentStatus().url === $(el).attr('href')
    ) {
      event.preventDefault()
      return false
    }
    if (
      !Barba.Pjax.originalPreventCheck(event, el) ||
      $(event.toElement).closest(IGNORE_LINK).length > 0 ||
      /.(png|gif|jpg|pdf|jpeg|mp4)/.test(el.href.toLowerCase())
    ) {
      return false
    }
    return true
  }

  // イベント登録
  Barba.Dispatcher.on('initStateChange', currentStatus => {
    if (Barba.HistoryManager.history.length > 1) {
      util.timeout = setTimeout(() => util.href(currentStatus.url), TIMEOUT)
      util.sendGoogleAnalytics(currentStatus.url)
    }
  })

  Barba.Dispatcher.on(
    'newPageReady',
    (currentStatus, oldStatus, container, newPageRawHTML) => {
      clearInterval(util.timeout)
      if (Barba.HistoryManager.history.length > 1) {
        util.localProxyFix(WRAPPER_ID, CONTAINER_CLASS)
        util.updateBodyClass(newPageRawHTML)
        util.updateHeadMeta(newPageRawHTML)
        util.resetContactForm7()
        util.resetMatchheight()
      }
    }
  )

  Barba.Dispatcher.on('transitionCompleted', () => util.addActiveClass())

  // プリフェッチ
  Barba.Prefetch.init()

  // 実行
  Barba.Pjax.start()
}

export default pjax
