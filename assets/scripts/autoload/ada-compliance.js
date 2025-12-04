function handleFirstTab(e) {
  let key = e.key || e.keyCode;
  if (key === 'Tab' || key === '9') {
    $('body').removeClass('no-outline');

    window.removeEventListener('keydown', handleFirstTab);
    window.addEventListener('mousedown', handleMouseDownOnce);
  }
}

function handleMouseDownOnce() {
  $('body').addClass('no-outline');

  window.removeEventListener('mousedown', handleMouseDownOnce);
  window.addEventListener('keydown', handleFirstTab);
}

window.addEventListener('keydown', handleFirstTab);
