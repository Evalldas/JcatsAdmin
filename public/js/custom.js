/**
 * Assign class "active" to the currently active navbar a element
 */
$(document).ready(function() {
    $('a[href$="' + location.pathname + '"]').addClass('active');
  });