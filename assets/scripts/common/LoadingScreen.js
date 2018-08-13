import { TimelineMax, TweenMax, Circ, Power4 } from 'gsap'
import imagesLoaded from 'imagesloaded'

const $body = $(document.body)
const $screen = $body.find('.loadingScreen')
const $screenBg = $screen.find('.loadingScreen__bg')
const $screenTitle = $screen.find('.loadingScreen__title')
const $progressbar = $body.find('.progressbar')
const $container = $body.find('.wrap')
const $svg = $screenTitle.find('svg')

class LoadingScreen {
  constructor() {
    this.loadedImgCount = 0
    this.loadingProgress = 0
    this.callback = null
    this.imgLoaded = null
    this.tweenOption = {
      ease: Circ.easeOut,
      force3d: true,
    }
  }

  progressBarCount(imgLoaded) {
    this.loadedImgCount += 1
    this.loadingProgress = (this.loadedImgCount / imgLoaded.images.length) * 100
    TweenMax.to($progressbar, 0.3, { width: this.loadingProgress + '%' })
    if (this.loadingProgress >= 100) {
      return
    }
  }

  noImageForceComplete() {
    const d = new $.Deferred()
    TweenMax.to($progressbar, 0.6, {
      width: '100%',
      onComplete: () => d.resolve(),
    })
    return d.promise()
  }

  start() {
    const d = new $.Deferred()
    this.loadedImgCount = 0
    this.loadingProgress = 0
    this.imgLoaded = imagesLoaded('#wrap *', { background: true })
    TweenMax.set($progressbar, { autoAlpha: 1 })
    if (this.imgLoaded.images.length) {
      this.imgLoaded.on('progress', () => this.progressBarCount(this.imgLoaded))
      this.imgLoaded.on('done', () => d.resolve())
    } else {
      this.noImageForceComplete().then(() => d.resolve())
    }
    return d.promise()
  }

  on(isFirst) {
    const d = new $.Deferred()
    $screen.addClass('js-on-screen')
    $body.attr({ 'data-js-menu': 'no-scroll' })
    new TimelineMax(this.tweenOption)
      .set($screen, { display: 'block' })
      .set($screenTitle, { autoAlpha: 0 })
      .set($screenBg, { y: isFirst ? '0%' : '-100%' })
      .to($screenTitle, 0.36, { autoAlpha: 1, autoRound: true })
      .to(
        $screenBg,
        0.4,
        {
          y: '0%',
          ease: Power4.easeOut,
          onComplete: () => d.resolve(),
        },
        '-=0.36'
      )

    return d.promise()
  }

  off() {
    const d = new $.Deferred()
    $screen.removeClass('js-on-screen')
    $body.attr({ 'data-js-menu': '' })
    new TimelineMax(this.tweenOption)
      .set($container, { autoAlpha: 0, y: '-60px' })
      .to($screenBg, 1, { ease: Power4.easeOut, y: '100%' })
      .to($svg.find('path').not('[fill="none"]'), 1, { fill: '#fff' }, '-=1.5')
      .to(
        $container,
        1.5,
        {
          autoAlpha: 1,
          clearProps: 'all',
          ease: Power4.easeOut,
          y: '0',
        },
        '-=0.6'
      )
      .to(
        $screenTitle,
        1.5,
        {
          autoAlpha: 0,
          autoRound: true,
          filter: 'blur(30px)',
        },
        '-=1.5'
      )
      .set($screen, { display: 'none' })
      .to(
        $progressbar,
        0.3,
        {
          autoAlpha: 0,
          onComplete: () => d.resolve(),
        },
        '-=1'
      )

    return d.promise()
  }

  init() {
    $screen.addClass('js-on-screen')
    $body.attr({ 'data-js-menu': 'no-scroll' })
    new TimelineMax(this.tweenOption)
      .set($screen, { display: 'block' })
      .set($screenBg, { y: '0%' })
  }
}
export default LoadingScreen
