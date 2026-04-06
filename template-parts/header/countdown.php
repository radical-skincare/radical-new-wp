<style>
.countdown-timer {
  padding: 8px 0;
  font-size: 14px;
  color: #fff;
}
.countdown-text {
  margin-right: 8px;
}
.countdown-display {
  font-weight: bold;
  font-family: 'Courier New', monospace;
  letter-spacing: 1px;
}
</style>
<div class="main-header_top-navbar">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg text-center">
        <div class="countdown-timer">
          <span class="countdown-text">Secret Santa Sale sale ends in:</span>
          <span class="countdown-display" id="countdown-display">00:00:00</span>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
(function($) {
'use strict';
$(document).ready(function() {
  // End date: December 28th 2025 11:59PM PST
  const endDate = new Date('2025-12-28T23:59:00-07:00');
  const $countdownDisplay = $('#countdown-display');
  function updateCountdown() {
    const now = new Date();
    const timeLeft = endDate - now;
    if (timeLeft <= 0) {
      $countdownDisplay.text('00:00:00');
      return;
    }
    // Calculate hours, minutes, and seconds
    let hours = Math.floor(timeLeft / (1000 * 60 * 60));
    let minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
    let seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
    // Format with leading zeros
    hours = String(hours).padStart(2, '0');
    minutes = String(minutes).padStart(2, '0');
    seconds = String(seconds).padStart(2, '0');
    // Update display
    $countdownDisplay.text(hours + ':' + minutes + ':' + seconds);
  }
  // Update immediately
  updateCountdown();
  // Update every second
  setInterval(updateCountdown, 1000);
});
})(jQuery);
</script>
