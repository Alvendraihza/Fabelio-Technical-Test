const $$ = elem => document.querySelectorAll(elem);


document.addEventListener('DOMContentLoaded', function() {
  $$('#navbarCollapse a').forEach(el => {
    // console.log(el.href)
    // console.log(location.href)
    if (el.href === location.href) {
      el.classList.add('active','text-primary');
      return false;
    }
  })
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
  $('.zoom').elevateZoom()
  $('[data-fancybox="gallery"]').fancybox({
    afterClose: function() {
      setTimeout(function() {
        $('[data-toggle="tooltip"]').tooltip('hide');
      },10)
    }
  })
})