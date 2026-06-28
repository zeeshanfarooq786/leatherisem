var clothing_store_Keyboard_loop = function (elem) {
  var clothing_store_tabbable = elem.find('select, input, textarea, button, a').filter(':visible');
  var clothing_store_firstTabbable = clothing_store_tabbable.first();
  var clothing_store_lastTabbable = clothing_store_tabbable.last();
  clothing_store_firstTabbable.focus();

  clothing_store_lastTabbable.on('keydown', function (e) {
    if ((e.which === 9 && !e.shiftKey)) {
      e.preventDefault();
      clothing_store_firstTabbable.focus();
    }
  });

  clothing_store_firstTabbable.on('keydown', function (e) {
    if ((e.which === 9 && e.shiftKey)) {
      e.preventDefault();
      clothing_store_lastTabbable.focus();
    }
  });

  elem.on('keyup', function (e) {
    if (e.keyCode === 27) {
      elem.hide();
    };
  });
};