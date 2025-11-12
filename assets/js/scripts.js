/* =======================================================
   File: assets/js/scripts.js
   Version: StarVel Admin Layout - Stable Build 2025
   ======================================================= */

/**
 * Mục đích:
 * - Quản lý sidebar toggle, hiệu ứng fixed header, smooth scroll.
 * - Đảm bảo không xung đột jQuery hoặc passive listener warning.
 */

(function ($) {
  "use strict";

  // ===== Toggle sidebar =====
  let toggle = true;
  $(".sidebar-icon").on("click", function (e) {
    e.preventDefault();
    if (toggle) {
      $(".page-container")
        .addClass("sidebar-collapsed")
        .removeClass("sidebar-collapsed-back");
      $("#menu span").css({ position: "absolute" });
    } else {
      $(".page-container")
        .removeClass("sidebar-collapsed")
        .addClass("sidebar-collapsed-back");
      setTimeout(function () {
        $("#menu span").css({ position: "relative" });
      }, 400);
    }
    toggle = !toggle;
  });

  // ===== Fixed header khi cuộn =====
  $(window).on("scroll", function () {
    const scrollTop = $(window).scrollTop();
    const navOffset = $(".header-main").offset().top;
    if (scrollTop >= navOffset) {
      $(".header-main").addClass("fixed");
    } else {
      $(".header-main").removeClass("fixed");
    }
  });

  // ===== NiceScroll (custom scrollbar) =====
  // Chrome cảnh báo về passive listener → ta sửa lại option cho đúng.
  if ($.fn.niceScroll) {
    $("html").niceScroll({
      cursorcolor: "#888",
      cursorwidth: "6px",
      cursorborderradius: "3px",
      scrollspeed: 60,
      mousescrollstep: 30,
      background: "#f1f1f1",
      autohidemode: true,
      passive: true // để tránh cảnh báo trong Chrome
    });
  }

  // ===== BasicTable (tự động responsive cho bảng) =====
  $(document).ready(function () {
    if ($.fn.basictable) {
      $("table").basictable({
        breakpoint: 768,
        forceResponsive: true
      });
    }
  });

  // ===== Tooltip bootstrap =====
  $(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });

})(jQuery);
