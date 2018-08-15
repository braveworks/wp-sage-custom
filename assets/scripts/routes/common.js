import pjax from 'scripts/common/pjax'

export default {
  init() {
    // JavaScript to be fired on all pages
    pjax()
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
}
