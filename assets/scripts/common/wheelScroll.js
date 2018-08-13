import { TweenMax, Power4 } from 'gsap'
import 'gsap/src/uncompressed/plugins/ScrollToPlugin'

const $window = $(window)

const jsScroll = event => {
  let delta = 0

  if (!event) {
    event = window.event
  }

  if (event.wheelDelta) {
    delta = event.wheelDelta / 120
  } else if (event.detail) {
    delta = -event.detail / 3
  }

  if (delta) {
    const scrollTop = $window.scrollTop()
    const finScroll = scrollTop - parseInt(delta * 100) * 3

    TweenMax.to($window, 2, {
      scrollTo: {
        y: finScroll,
        autoKill: true,
      },
      ease: Power4.easeOut,
      autoKill: true,
      overwrite: 5,
    })
  }

  if (event.preventDefault) {
    event.preventDefault()
  }

  event.returnValue = false
}

const wheelScroll = () => {
  document.onmousewheel = () => jsScroll()
  if (document.addEventListener) {
    document.addEventListener('DOMMouseScroll', jsScroll, false)
  }
}

export default wheelScroll
