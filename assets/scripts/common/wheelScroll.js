import isMobile from 'ismobilejs'
import { TweenMax, Power4 } from 'gsap'
import 'gsap/src/uncompressed/plugins/ScrollToPlugin'

class WheelScroll {
  constructor() {
    this.delta = 0
  }

  static update(event) {
    if (!event) {
      event = window.event
    }

    if (event.wheelDelta) {
      this.delta = event.wheelDelta / 120
    } else if (event.detail) {
      this.delta = -event.detail / 3
    }

    if (this.delta) {
      const finScroll = window.scrollY - parseInt(this.delta * 100) * 3

      TweenMax.to(window, 2.2, {
        scrollTo: {
          y: finScroll,
          autoKill: true,
        },
        ease: Power4.easeOut,
        autoKill: true,
        overwrite: 5,
        force3D: true,
      })
    }

    if (event.preventDefault) {
      event.preventDefault()
    }

    event.returnValue = false
  }
}

function scrollInit() {
  if (!isMobile.any) {
    if (document.addEventListener) {
      document.addEventListener('wheel', WheelScroll.update, false)
    } else {
      document.onmousewheel = () => WheelScroll.update()
    }
  }
}

export default scrollInit()
