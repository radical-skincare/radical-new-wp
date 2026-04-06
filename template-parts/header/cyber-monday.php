<style>
#cyber-monday {
  background-color: #000;
  padding: 1rem 0;
  color: #fff;
}

#cyber-monday .cyber-monday_text {
  margin-bottom: 0.75rem;
  font-size: 0.875rem;
  line-height: 1.5;
}

@media (min-width: 992px) {
  #cyber-monday .cyber-monday_text {
    font-size: 1rem;
    margin-bottom: 1rem;
  }
}

#cyber-monday .text-teal {
  color: #20b2aa;
}

#cyber-monday .text-pink {
  color: #ff69b4;
}

#cyber-monday .cyber-monday_countdown-wrapper .cyber-monday_ends-text {
  margin-bottom: 0.25rem;
  font-size: 0.75rem;
  color: #fff;
}

@media (min-width: 992px) {
  #cyber-monday .cyber-monday_countdown-wrapper .cyber-monday_ends-text {
    font-size: 0.875rem;
  }
}

#cyber-monday .cyber-monday_countdown-wrapper .cyber-monday_date {
  margin-bottom: 0.5rem;
  font-size: 0.75rem;
  color: #fff;
}

@media (min-width: 992px) {
  #cyber-monday .cyber-monday_countdown-wrapper .cyber-monday_date {
    font-size: 0.875rem;
  }
}

#cyber-monday .cyber-monday_countdown .countdown-time {
  display: inline-block;
  font-size: 1.25rem;
  font-weight: 700;
  font-family: "Josefin Sans", sans-serif;
  color: #20b2aa;
  letter-spacing: 0.1em;
}

#cyber-monday .cyber-monday_instruction p {
  margin-bottom: 0.25rem;
  font-size: 0.875rem;
}

#cyber-monday .cyber-monday_instruction .btn {
  margin-top: 0.25rem;
}

@media (min-width: 992px) {
  #cyber-monday .cyber-monday_countdown .countdown-time {
    font-size: 1.5rem;
  }
}
</style>
<section id="cyber-monday">
  <div class="container">
    <div class="row align-items-center justify-content-center">
      <div class="col-12 text-center">
        <p class="cyber-monday_text">
          <span class="text-teal">GET 20% OFF Everything</span> this <span class="text-pink">Cyber Monday</span>, plus <span class="text-teal">FREE Gift with Purchase</span>.
        </p>
        <div class="cyber-monday_countdown-wrapper">
          <p class="cyber-monday_ends-text">Sale Ends In:</p>
          <p class="cyber-monday_date">Dec 1st 11:59PM PST</p>
          <div class="cyber-monday_countdown" id="cyber-monday-countdown">
            <span class="countdown-time">00:00:00</span>
          </div>
        </div>
        <!-- Instructional text added for Cyber Monday -->
        <div class="cyber-monday_instruction mt-2">
          <p class="mb-2"><strong>Claim your Natural Shimmer Highlighter gift on the <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="text-pink">Cart page</a>.</strong></p>
          <?php if ( ! is_cart() ) : ?>
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn btn-primary" aria-label="View cart & claim your Natural Shimmer Highlighter gift">View Cart</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
(function($) {
'use strict';

var countdownInterval = null;
var endDate = null;

function initCountdown() {
  var currentYear = new Date().getFullYear();
  endDate = new Date(Date.UTC(currentYear, 11, 2, 7, 59, 59));

  var now = new Date();
  if (now > endDate) {
    endDate.setUTCFullYear(currentYear + 1);
  }

  updateCountdown();

  countdownInterval = setInterval(function() {
    updateCountdown();
  }, 1000);
}

function updateCountdown() {
  var now = new Date();
  var distance = endDate.getTime() - now.getTime();

  if (distance < 0) {
    $('.countdown-time').text('00:00:00');
    if (countdownInterval) {
      clearInterval(countdownInterval);
    }
    return;
  }

  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  var formattedTime =
    String(hours).padStart(2, '0') + ':' +
    String(minutes).padStart(2, '0') + ':' +
    String(seconds).padStart(2, '0');

  $('.countdown-time').text(formattedTime);
}

$(document).ready(function() {
  if ($('#cyber-monday').length) {
    initCountdown();
  }
});
})(jQuery);
</script>
