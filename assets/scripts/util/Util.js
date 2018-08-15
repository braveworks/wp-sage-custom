class Util {
  static hasClass(el, className) {
    if (el.classList) {
      return el.classList.contains(className)
    } else {
      return new RegExp('(^| )' + className + '( |$)', 'gi').test(el.className)
    }
  }

  static addClass(el, className) {
    if (el.classList) {
      el.classList.add(className)
    } else {
      el.className += ' ' + className
    }
  }

  static removeClass(el, className) {
    if (el.classList) {
      el.classList.remove(className)
    } else {
      el.className = el.className.replace(
        new RegExp(
          '(^|\\b)' + className.split(' ').join('|') + '(\\b|$)',
          'gi'
        ),
        ' '
      )
    }
  }

  static closest(el, selector) {
    const matchesSelector =
      el.matches ||
      el.webkitMatchesSelector ||
      el.mozMatchesSelector ||
      el.msMatchesSelector

    while (el) {
      if (matchesSelector.call(el, selector)) {
        return el
      } else {
        el = el.parentElement
      }
    }
    return null
  }

  static shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
      const r = Math.floor(Math.random() * (i + 1))
      const tmp = array[i]
      array[i] = array[r]
      array[r] = tmp
    }

    return array
  }
}
export default Util
