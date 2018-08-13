const addTableResponsiveClass = () => {
  if ($(document.body).hasClass('single-post')) {
    const $table = $('.postSingle').find('table')
    $table.wrap('<div class="table-responsive"></div>')
  }
}

export default addTableResponsiveClass
