<style>
.product-countdown .countdown-timer {
  padding: 8px 0;
  font-size: 14px;
}
.product-countdown .countdown-text {
  margin-right: 8px;
}
.product-countdown .countdown-display {
  font-weight: bold;
  letter-spacing: 1px;
}
.product-countdown .countdown-display_item {
  display: inline-block;
  background-color: #333333;
  color: white;
  padding: 8px;
  border-radius: 8px;
}
</style>
<div class="product-countdown">
  <div class="row justify-content-center">
    <div class="col-lg text-center">
      <div class="countdown-timer">
        <span class="d-block countdown-text">Sale ends in:</span>
        <div class="countdown-display" id="countdown-display">
          <span class="countdown-display_item">00</span>
          <span class="countdown-display_item-separator">:</span>
          <span class="countdown-display_item">00</span>
          <span class="countdown-display_item-separator">:</span>
          <span class="countdown-display_item">00</span>
          <span class="countdown-display_item-separator">:</span>
          <span class="countdown-display_item">00</span>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
(function($) {
'use strict';
$(document).ready(function() {
  // End date: January 11th 2026 11:59PM PST
  const endDate = new Date('2026-01-11T23:59:00-08:00');
  const $countdownItems = $('#countdown-display .countdown-display_item');
  function updateCountdown() {
    const now = new Date();
    const timeLeft = endDate - now;
    if (timeLeft <= 0) {
      $countdownItems.eq(0).text('00');
      $countdownItems.eq(1).text('00');
      $countdownItems.eq(2).text('00');
      $countdownItems.eq(3).text('00');
      return;
    }
    // Calculate days, hours, minutes, and seconds
    let days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
    let hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    let minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
    let seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
    // Format with leading zeros
    days = String(days).padStart(2, '0');
    hours = String(hours).padStart(2, '0');
    minutes = String(minutes).padStart(2, '0');
    seconds = String(seconds).padStart(2, '0');
    // Update individual span elements (days, hours, minutes, seconds)
    $countdownItems.eq(0).text(days);
    $countdownItems.eq(1).text(hours);
    $countdownItems.eq(2).text(minutes);
    $countdownItems.eq(3).text(seconds);
  }
  // Update immediately
  updateCountdown();
  // Update every second
  setInterval(updateCountdown, 1000);
});
})(jQuery);
</script>
