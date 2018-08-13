const $window = $(window)
const $document = $(document)
const $body = $(document.body)
const gnav = '.gnav'
const btnOpen = '.gnav-openBtn'
const btnClose = '.gnav-closeBtn'
const OPEN_CLASS = 'js-menu-open'
const lG = '(min-width: 992px)'

let isFirst = true

const scrollToClose = () => {
  if (isFirst && $body.hasClass(OPEN_CLASS) && $window.scrollTop() > 160) {
    $body.removeClass(OPEN_CLASS)
    isFirst = false
  }
}

const init = () => {
  $document
    .on('click', btnOpen, () => $body.addClass(OPEN_CLASS))
    .on('click', btnClose, () => $body.removeClass(OPEN_CLASS))
    .on('click', event => {
      if (
        !$(event.target).closest(gnav).length &&
        !$(event.target).closest(btnOpen).length &&
        $body.hasClass(OPEN_CLASS)
      ) {
        $body.removeClass(OPEN_CLASS)
      }
    })
  $window.on('scroll', scrollToClose)
}

const openHome = () => {
  if ($body.hasClass('home') && window.matchMedia(lG).matches) {
    if ($window.scrollTop() < 160) {
      $body.addClass(OPEN_CLASS)
    } else {
      isFirst = false
    }
  }
}

export default {
  init,
  openHome,
}
