(function ($, Drupal) {
  Drupal.behaviors.adminToolbarBehavior = {
    attach: function (context, settings) {

      $('#admin-toolbar .dropdown-trigger', context).once('adminToolbar').each(function (idx, ele) {
        $(ele).on('mouseenter', function(evt) {
          var drop = $(this).siblings('.dropdown')[0];
          $('.dropdown').addClass('d-none');
          $(drop).removeClass('d-none')
        });
      });


      $('#admin-toolbar .dropdown', context).once('adminToolbarDropdown').each(function (idx, ele) {
        $(ele).on('mouseleave', function(evt) {
          $(ele).addClass('d-none');
        });
      });

    }
  }
})(jQuery, Drupal);
