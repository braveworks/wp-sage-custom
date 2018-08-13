import { TweenMax } from 'gsap'
import 'waypoints/lib/noframework.waypoints'

const Waypoint = window.Waypoint
const DEFAULT_OFFSET = '100%'
const DATA_ATTR = 'data-waypoint'
const DEFAULT_INVIEW_CLASS = 'js-inview'

class ScrollAnimetion {
  constructor() {
    this.document = 'document'
  }

  static enableAll() {
    Waypoint.enableAll()
  }

  static setClass() {
    const target = $(document).find(`[${DATA_ATTR}]`)
    Array.prototype.forEach.call(target, elm => {
      const className = $(elm).attr(DATA_ATTR) || DEFAULT_INVIEW_CLASS
      const offset = $(elm).attr(`${DATA_ATTR}-offset`) || DEFAULT_OFFSET
      new Waypoint({
        element: elm,
        offset: offset,
        handler() {
          $(elm).addClass(className)
        },
      })
    })
  }

  static stagger() {
    const target = $(document).find('[data-stagger="parent"]')
    Array.prototype.forEach.call(target, elm => {
      const child = $(elm).find('[data-stagger="child"]')
      TweenMax.set(child, {
        autoAlpha: 0,
        y: 30,
      })
      new Waypoint({
        element: elm,
        offset: DEFAULT_OFFSET,
        handler() {
          TweenMax.staggerTo(
            child,
            0.8,
            {
              autoAlpha: 1,
              y: 0,
            },
            0.2
          )
        },
      })
    })
  }

  static staggerList() {
    const target = $(document).find('.js-stagger-list')
    Array.prototype.forEach.call(target, elm => {
      const child = $(elm).find('li')
      TweenMax.set(child, {
        autoAlpha: 0,
        y: 30,
      })
      new Waypoint({
        element: elm,
        offset: DEFAULT_OFFSET,
        handler() {
          TweenMax.staggerTo(
            child,
            0.8,
            {
              autoAlpha: 1,
              y: 0,
            },
            0.2
          )
        },
      })
    })
  }

  static blurIn() {
    const target = $(document).find('[data-blur-in]')
    TweenMax.set(target, {
      filter: 'blur(30px)',
      opacity: 0,
    })
    Array.prototype.forEach.call(target, elm => {
      new Waypoint({
        element: elm,
        offset: DEFAULT_OFFSET,
        handler() {
          TweenMax.to(elm, 0.8, {
            filter: 'blur(0)',
            opacity: 1,
          })
        },
      })
    })
  }

  static init() {
    ScrollAnimetion.blurIn()
    ScrollAnimetion.setClass()
    ScrollAnimetion.stagger()
    ScrollAnimetion.staggerList()
  }
}
export default ScrollAnimetion
