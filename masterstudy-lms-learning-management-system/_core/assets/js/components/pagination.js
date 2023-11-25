"use strict";

(function ($) {
  $(document).ready(function () {
    var pagesContainer = $('.masterstudy-pagination');
    var pagesWrapper = $('.masterstudy-pagination').find('.masterstudy-pagination__wrapper');
    var pagesList = pagesContainer.find('.masterstudy-pagination__list');
    var scrollButtonNext = pagesContainer.find('.masterstudy-pagination__button-next');
    var scrollButtonPrev = pagesContainer.find('.masterstudy-pagination__button-prev');
    var numericFields = ['max_visible_pages', 'total_pages', 'current_page', 'item_width'];
    numericFields.forEach(function (field) {
      pages_data[field] = parseInt(pages_data[field]);
    });
    if (window.matchMedia('(max-width: 576px)').matches) {
      pages_data.max_visible_pages = Math.round(pages_data.max_visible_pages / 2);
    }
    var containerWidth = pages_data.item_width * pages_data.max_visible_pages,
      currentPosition = 0,
      currentPage = pages_data.current_page,
      totalPages = pages_data.total_pages,
      centeredPage = Math.round(pages_data.max_visible_pages / 2),
      maxPosition = pages_data.item_width * (totalPages - pages_data.max_visible_pages),
      noScroll = totalPages <= pages_data.max_visible_pages;
    if (pages_data.max_visible_pages < totalPages) {
      pagesWrapper.css('width', containerWidth);
    } else {
      pagesWrapper.css('width', totalPages * pages_data.item_width);
    }
    scrollButtonNext.click(function (e) {
      e.preventDefault();
      if (currentPage < totalPages) {
        currentPage = currentPage + 1;
      }
      if (currentPage === totalPages) {
        $(this).addClass('masterstudy-pagination__button_disabled');
      } else {
        $(this).removeClass('masterstudy-pagination__button_disabled');
      }
      if (currentPage !== 1) {
        $(this).parent().find('.masterstudy-pagination__button-prev').removeClass('masterstudy-pagination__button_disabled');
      }
      if (currentPage > centeredPage && currentPosition < maxPosition) {
        currentPosition += pages_data.item_width;
        pagesList.animate({
          'left': -currentPosition + 'px'
        }, 50);
      }
      pagesList.find("[data-id=\"".concat(currentPage, "\"]")).parent().siblings().removeClass('masterstudy-pagination__item_current');
      pagesList.find("[data-id=\"".concat(currentPage, "\"]")).parent().addClass('masterstudy-pagination__item_current');
    });
    scrollButtonPrev.click(function (e) {
      e.preventDefault();
      if (currentPage > 1) {
        currentPage = currentPage - 1;
      }
      if (currentPage === 1) {
        $(this).addClass('masterstudy-pagination__button_disabled');
      } else {
        $(this).removeClass('masterstudy-pagination__button_disabled');
      }
      if (currentPage !== totalPages) {
        $(this).parent().find('.masterstudy-pagination__button-next').removeClass('masterstudy-pagination__button_disabled');
      }
      if (!currentPage < centeredPage && currentPage < totalPages - centeredPage + 1 && currentPosition > 0) {
        currentPosition -= pages_data.item_width;
        pagesList.animate({
          'left': -currentPosition + 'px'
        }, 50);
      }
      pagesList.find("[data-id=\"".concat(currentPage, "\"]")).parent().siblings().removeClass('masterstudy-pagination__item_current');
      pagesList.find("[data-id=\"".concat(currentPage, "\"]")).parent().addClass('masterstudy-pagination__item_current');
    });
    $('.masterstudy-pagination__item-block').click(function () {
      currentPage = $(this).data('id');
      var container = $(this).closest('.masterstudy-pagination');
      if (currentPage < centeredPage) {
        currentPosition = 0;
      } else if (currentPage > totalPages - centeredPage + 1) {
        currentPosition = noScroll ? 0 : maxPosition;
      } else {
        currentPosition = (currentPage - centeredPage) * pages_data.item_width;
      }
      if (currentPage === 1) {
        container.find('.masterstudy-pagination__button-prev').addClass('masterstudy-pagination__button_disabled');
      } else {
        container.find('.masterstudy-pagination__button-prev').removeClass('masterstudy-pagination__button_disabled');
      }
      if (currentPage === totalPages) {
        container.find('.masterstudy-pagination__button-next').addClass('masterstudy-pagination__button_disabled');
      } else {
        container.find('.masterstudy-pagination__button-next').removeClass('masterstudy-pagination__button_disabled');
      }
      $(this).parent().siblings().removeClass('masterstudy-pagination__item_current');
      $(this).parent().addClass('masterstudy-pagination__item_current');
      pagesList.animate({
        'left': -currentPosition + 'px'
      }, 50);
    });
  });
})(jQuery);