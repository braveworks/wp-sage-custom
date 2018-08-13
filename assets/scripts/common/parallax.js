import Rellax from 'rellax'

const RELLAX_CLASS = '.rellax'
const target = document.querySelectorAll(RELLAX_CLASS)

export default () => {
  if (target.length) {
    new Rellax(RELLAX_CLASS)
  }
}
