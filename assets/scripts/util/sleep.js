/**
 * ユーティリティ：指定ミリ秒待ってから関数を実行
 *
 * 例:
 * sleep(500).then(callback);
 *
 */
const sleep = function(ms) {
  const d = new $.Deferred()
  setTimeout(() => d.resolve(), ms)
  return d.promise()
}
export default sleep
