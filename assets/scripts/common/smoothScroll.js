/**
 * スムーススクロール
 *
 * require: 'GSAP' & 'Scroll Plugin'
 * https://greensock.com/
 * https://greensock.com/ScrollToPlugin
 *
 */

import { TweenMax, Circ } from 'gsap'
import 'gsap/src/uncompressed/plugins/ScrollToPlugin'
import isMobile from 'ismobilejs'

const smoothScroll = () => {
  const EASE = Circ.easeInOut
  const FORCE3D = true
  const SCROLL_DURATION = 1
  const $document = $(document)
  const Scroll = {}

  // 除外セレクタ
  const ignoreElement = [
    '.no-scroll',
    '[data-toggle]',
    '[href="#"]',
    '[href="#0"]',
    '[target^="_"]',
  ].join(', ')

  // ハッシュリンクのスクロール
  Scroll.hash = e => {
    if (e.currentTarget.hash) {
      e.preventDefault()
      $(document.body).removeClass('js-menu-open')
      TweenMax.to(window, SCROLL_DURATION, {
        scrollTo: {
          autoKill: !isMobile.any,
          // offsetY: $('.navCategoryWrapper').outerHeight(),
          y: e.currentTarget.hash,
        },
        ease: EASE,
        force3D: FORCE3D,
      })
    }
  }

  // [トップに戻る]のスクロール
  Scroll.pageTop = () => {
    TweenMax.to(window, SCROLL_DURATION, {
      scrollTo: {
        autoKill: !isMobile.any,
        y: 0,
      },
      ease: EASE,
      force3D: FORCE3D,
    })
  }

  if (typeof TweenMax === 'function') {
    $document
      .on('click', `a[href^="#"]:not(${ignoreElement})`, Scroll.hash)
      .on('click', '.goTop', Scroll.pageTop)
  }
}

export default smoothScroll()
