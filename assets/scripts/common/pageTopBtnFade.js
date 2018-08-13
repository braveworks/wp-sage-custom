import { TweenMax } from 'gsap'

const pageTopBtnFade = () => {
  const $window = $(window)
  const $topBtn = $('.goTop')
  const addVisibleClass = () => {
    const isActive = () => !!($window.scrollTop() > 160)
    TweenMax.to($topBtn, 0.6, {
      autoAlpha: isActive() ? 1 : 0,
      display: isActive() ? 'block' : 'none',
    })
  }

  // 初期化
  addVisibleClass()
  $window.on('scroll', addVisibleClass)
}
export default pageTopBtnFade()
