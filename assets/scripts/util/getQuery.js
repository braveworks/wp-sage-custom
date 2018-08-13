/**
 * URLクエリを取得
 */

const getQuery = () => {
  if (window.location.search === '') return
  const variables = window.location.search.split('?')[1].split('&')
  const obj = {}
  variables.forEach(function(v) {
    const variable = v.split('=')
    obj[variable[0]] = Number(variable[1])
  })
  return obj
}

export default getQuery
