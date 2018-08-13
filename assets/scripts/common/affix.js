/**
 * スクロールでヘッダー固定
 */

export default () => {
  const $window = $(window)
  const $sticky = $('[data-affix="sticky"]')
  const $target = $('[data-affix="target"]')
  const settClass = event =>
    $sticky.toggleClass(
      'js-fixed',
      $(event.currentTarget).scrollTop() > $target.outerHeight()
    )

  $window.on('scroll', settClass)
}
