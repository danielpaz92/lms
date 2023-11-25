"use strict";

(function ($) {
  $(document).ready(function () {
    /* Show Dropdown Payments */
    var animationActive = false;
    $('.masterstudy-buy-button').on('click', function () {
      toggleAnimation();
    });
    $('.masterstudy-buy-button_plans-dropdown').on('click', function (event) {
      event.stopPropagation();
    });
    function toggleAnimation() {
      animationActive = !animationActive;
      $('.masterstudy-buy-button').toggleClass('dropdown-show', animationActive);
    }
    $(document).on('click', function (event) {
      if (!$(event.target).closest('.masterstudy-buy-button').length && animationActive) {
        toggleAnimation();
      }
    });
    /* End Show Dropdown Payments */

    /* Link for LMS checkout */
    $('[data-purchased-course]').on('click', function (event) {
      event.preventDefault();
      var item_id = $(this).attr('data-purchased-course');
      if (typeof item_id === 'undefined') {
        window.location = $(this).attr('href');
        return false;
      }
      $.ajax({
        url: masterstudy_buy_button_data.ajax_url,
        dataType: 'json',
        context: this,
        data: {
          action: 'stm_lms_add_to_cart',
          nonce: masterstudy_buy_button_data.get_nonce,
          item_id: masterstudy_buy_button_data.item_id
        },
        beforeSend: function beforeSend() {
          $(this).find('.masterstudy-buy-button__title').addClass('masterstudy-buy-button__loading');
        },
        complete: function complete(data) {
          var data = data['responseJSON'];
          $(this).find('.masterstudy-buy-button__title').removeClass('masterstudy-buy-button__loading');
          $(this).find('.masterstudy-buy-button__title').text(data['text']);
          if (data['cart_url']) {
            if (data['redirect']) window.location = data['cart_url'];
            $(this).attr('href', data['cart_url']).removeAttr('data-purchased-course');
          }
        }
      });
    });
    /* End Link for LMS checkout */
  });
})(jQuery);