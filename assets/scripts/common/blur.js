/**
 *  リンククリック時のブラーを強制消去
 */

export default (() =>
  $(document).on('click touchend', 'a, button', e =>
    $(e.delegateTarget.activeElement).blur()
  ))()
