import debounce from 'lodash/debounce'
import windowSize from './WindowSize'
import { TweenLite } from 'gsap'

// const config = {
//   isTop: true
// };

class FakeScroll {
  constructor(target, speed) {
    this.target = target || document.querySelector('.wrapper') || null
    this.ticking = false
    this.height = 0
    this.position = {
      x: 0,
      y: 0,
      oldX: 0,
      oldY: 0,
    }
    this.scroll = {
      y: 0,
      power: 0,
    }
    this.speed = typeof speed == 'undefined' ? 0.1 : speed

    if (this.target) {
      this._setup()
    }
  }

  _onScroll() {
    this.scroll.power += 100
    this.scroll.y = window.pageYOffset || document.documentElement.scrollTop
    if (!this.ticking) {
      window.requestAnimationFrame(() => this._update())
    }
    this.ticking = true
  }

  _update() {
    this.position.y += (this.scroll.y - this.position.y) * this.speed
    this.position.y = Number(this.position.y.toFixed(1))
    const dis = this.scroll.y - this.position.y
    if (dis < 1 && dis > -1) {
      this._positionUpdate()
      this.ticking = false
    } else {
      window.requestAnimationFrame(() => this._update())
    }
    this._positionUpdate()
    this.position.oldY = this.position.y
  }

  _sizeUpdate() {
    this.height = this.target.offsetHeight
    TweenLite.set(document.body, { height: this.height })
    this._positionUpdate()
  }

  _positionUpdate() {
    TweenLite.set(this.target, { y: -this.position.y, force3D: true })
    // this.target.style.transform = `translate3d(0px,${-this.position.y}px,0)`;
  }

  _screenSize() {
    return windowSize()
  }

  _setup() {
    this.target.style.position = 'fixed'
    this._sizeUpdate()
    window.addEventListener('scroll', () => this._onScroll())
    window.addEventListener('resize', debounce(() => this._sizeUpdate(), 10))
  }
}
export default FakeScroll
