/**
 * cookieを取得したり入れたり
 */

import Cookies from 'js-cookie'

const getCookie = limit => {
  const cookieLimit = limit || 12 // 12時間をデフォルト
  const date = new Date()
  let visit = Number(Cookies.get('visit', Number) || 0) //cookieから回数取得

  date.setTime(date.getTime() + cookieLimit * 60 * 60 * 1000)
  visit += 1 //訪問回数を足す

  Cookies.set('visit', visit, { expires: date })

  return visit
}
export default getCookie
