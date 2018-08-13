const externalLink = () => {
  const $href = $(
    `a[href^="http"]:not([href*="${
      window.location.hostname
    }"]), a[href*=".pdf"]`
  )
  Array.prototype.forEach.call($href, elm =>
    $(elm)
      .addClass('external')
      .attr('target', '_blank')
  )
}
export default externalLink()
