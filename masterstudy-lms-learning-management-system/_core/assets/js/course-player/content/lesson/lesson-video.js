"use strict";

(function ($) {
  $(document).ready(function () {
    var videoPlayer = document.getElementById('masterstudy-course-player-lesson-video');
    var playButton = $('.masterstudy-course-player-lesson-video__play-button');
    if (videoPlayer) {
      playButton.click(function () {
        playButton.hide();
        videoPlayer.play();
      });
      videoPlayer.addEventListener('play', function () {
        playButton.hide();
      });
      videoPlayer.addEventListener('pause', function () {
        if (!window.matchMedia('(max-width: 576px)').matches) {
          playButton.show();
        }
      });
    }
  });
})(jQuery);