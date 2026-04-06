const ProductPurchaseOptions = {
  onLoad: function () {
    $('form.cart').each(function () {
      const $form = $(this);
      const $btn = $form.find('.single_add_to_cart_button');

      // Get product ID from button value attribute or other sources
      const productId = $btn.val() ||
                       $form.find('input[name="add-to-cart"]').val() ||
                       $form.find('input[name="product_id"]').val();

      // Initialize - select one-time purchase by default
      const $oneTimeRadio = $form.find('input[name="sub_plan"][value="one-time-purchase"]');
      if (!$form.find('input[name="sub_plan"]:checked').length) {
        // No option is selected, select one-time purchase by default
        $oneTimeRadio.prop('checked', true);
        $oneTimeRadio.closest('.list-group-item').addClass('is-selected');

        // Update WCSATT to match
        if (productId) {
          $form.find(`input[name="convert_to_sub_${productId}"]`).prop('checked', false);
          $form.find(`input[name="convert_to_sub_${productId}"][value="0"]`).prop('checked', true);
        }

        // Update hidden fields
        $form.find('#convert_to_sub').val('0');
        $form.find('#subscribe-to-action-input').val('no');
        $form.find('#refill_frequencies').prop('selectedIndex', 0);
        $form.find('select[name="convert_to_sub_dropdown"]').prop('selectedIndex', 0);

        $btn.prop('disabled', false).removeClass('disabled');
      } else {
        // Something is already selected, enable the button
        $btn.prop('disabled', false).removeClass('disabled');
      }

      // Click anywhere on card to toggle radio
      $form.on('click', '.woo-sub-options .list-group-item', function (e) {
        if (!$(e.target).is('input[type="radio"]')) {
          const $radio = $(this).find('input[type="radio"]');
          $radio.prop('checked', true).trigger('change');
        }
      });

      // Keyboard support: Enter/Space selects card
      $form
        .on('keydown', '.woo-sub-options .list-group-item', function (e) {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            const $radio = $(this).find('input[type="radio"]');
            $radio.prop('checked', true).trigger('change');
          }
        })
        .find('.woo-sub-options .list-group-item')
        .attr('tabindex', '0');

      // Change handler: sync hidden fields and enable button
      $form.on('change', 'input[name="sub_plan"]', function () {
        const isRefill = $(this).val() === 'refill';

        // Visual selection fallback for browsers without :has()
        $form
          .find('.woo-sub-options .list-group-item')
          .removeClass('is-selected');
        $(this).closest('.list-group-item').addClass('is-selected');

        if (isRefill) {
          const plan = $(this).data('plan');

          // Update all subscription-related fields
          $form.find('#convert_to_sub').val('1');
          $form.find('#subscribe-to-action-input').val('yes');
          $form.find('#refill_frequencies').val(plan);

          // Update WCSATT radio buttons to match the selected plan
          if (productId) {
            // Uncheck all WCSATT radios first
            $form.find(`input[name="convert_to_sub_${productId}"]`).prop('checked', false);
            // Check the matching plan
            $form.find(`input[name="convert_to_sub_${productId}"][value="${plan}"]`).prop('checked', true);
          }

          // Update dropdown field if it exists
          $form.find('select[name="convert_to_sub_dropdown"]').val(plan);

        } else {
          // Reset all subscription-related fields for one-time purchase
          $form.find('#convert_to_sub').val('0');
          $form.find('#subscribe-to-action-input').val('no');
          $form.find('#refill_frequencies').prop('selectedIndex', 0);

          // Select one-time option in WCSATT
          if (productId) {
            $form.find(`input[name="convert_to_sub_${productId}"]`).prop('checked', false);
            $form.find(`input[name="convert_to_sub_${productId}"][value="0"]`).prop('checked', true);
          }

          // Reset dropdown field if it exists
          $form.find('select[name="convert_to_sub_dropdown"]').prop('selectedIndex', 0);
        }

        $btn.prop('disabled', false).removeClass('disabled');
      });

      // Also listen for changes on WCSATT radio buttons to sync back
      $form.on('change', `input[name="convert_to_sub_${productId}"]`, function () {
        const selectedValue = $(this).val();

        // Prevent infinite loop by temporarily removing event handlers
        $form.off('change', 'input[name="sub_plan"]');

        if (selectedValue === '0') {
          // One-time purchase selected
          $form.find('input[name="sub_plan"]').prop('checked', false);
          $form.find('input[name="sub_plan"][value="one-time-purchase"]').prop('checked', true);

          // Update visual state
          $form.find('.woo-sub-options .list-group-item').removeClass('is-selected');
          $form.find('input[name="sub_plan"][value="one-time-purchase"]').closest('.list-group-item').addClass('is-selected');

          // Update hidden fields
          $form.find('#convert_to_sub').val('0');
          $form.find('#subscribe-to-action-input').val('no');
          $form.find('#refill_frequencies').prop('selectedIndex', 0);
          $form.find('select[name="convert_to_sub_dropdown"]').prop('selectedIndex', 0);

        } else {
          // Subscription selected - find matching plan
          const $matchingRadio = $form.find(`input[name="sub_plan"][data-plan="${selectedValue}"]`);
          if ($matchingRadio.length > 0) {
            $form.find('input[name="sub_plan"]').prop('checked', false);
            $matchingRadio.prop('checked', true);

            // Update visual state
            $form.find('.woo-sub-options .list-group-item').removeClass('is-selected');
            $matchingRadio.closest('.list-group-item').addClass('is-selected');

            // Update hidden fields
            $form.find('#convert_to_sub').val('1');
            $form.find('#subscribe-to-action-input').val('yes');
            $form.find('#refill_frequencies').val(selectedValue);
            $form.find('select[name="convert_to_sub_dropdown"]').val(selectedValue);
          }
        }

        // Re-attach the event handler
        setTimeout(() => {
          $form.on('change', 'input[name="sub_plan"]', function () {
            const isRefill = $(this).val() === 'refill';

            // Visual selection fallback for browsers without :has()
            $form
              .find('.woo-sub-options .list-group-item')
              .removeClass('is-selected');
            $(this).closest('.list-group-item').addClass('is-selected');

            if (isRefill) {
              const plan = $(this).data('plan');

              // Update all subscription-related fields
              $form.find('#convert_to_sub').val('1');
              $form.find('#subscribe-to-action-input').val('yes');
              $form.find('#refill_frequencies').val(plan);

              // Update WCSATT radio buttons to match the selected plan
              if (productId) {
                // Uncheck all WCSATT radios first
                $form.find(`input[name="convert_to_sub_${productId}"]`).prop('checked', false);
                // Check the matching plan

                $form.find(`input[name="convert_to_sub_${productId}"][value="${plan}"]`).prop('checked', true);
              }

              // Update dropdown field if it exists
              $form.find('select[name="convert_to_sub_dropdown"]').val(plan);

            } else {
              // Reset all subscription-related fields for one-time purchase
              $form.find('#convert_to_sub').val('0');
              $form.find('#subscribe-to-action-input').val('no');
              $form.find('#refill_frequencies').prop('selectedIndex', 0);

              // Select one-time option in WCSATT
              if (productId) {
                $form.find(`input[name="convert_to_sub_${productId}"]`).prop('checked', false);
                $form.find(`input[name="convert_to_sub_${productId}"][value="0"]`).prop('checked', true);
              }

              // Reset dropdown field if it exists
              $form.find('select[name="convert_to_sub_dropdown"]').prop('selectedIndex', 0);
            }

            $btn.prop('disabled', false).removeClass('disabled');
          });
        }, 10);

        $btn.prop('disabled', false).removeClass('disabled');
      });

      // Extra guard: prevent submission with no selection
      $form.on('submit', function (e) {
        const hasChoice = $form.find('input[name="sub_plan"]:checked').length > 0 ||
                         $form.find(`input[name="convert_to_sub_${productId}"]:checked`).length > 0;
        if (!hasChoice) {
          e.preventDefault();
          // subtle shake
          $btn.addClass('shake');
          setTimeout(() => $btn.removeClass('shake'), 400);
        }
      });

      // Reset on variation change (variable products)
      $form.on('found_variation reset_data', '.variations_form', function () {
        $form.find('input[name="sub_plan"]').prop('checked', false);
        $form
          .find('.woo-sub-options .list-group-item')
          .removeClass('is-selected');
        $form.find('#convert_to_sub').val('0');
        $form.find('#subscribe-to-action-input').val('no');
        $form.find('#refill_frequencies').prop('selectedIndex', 0);

        // Reset WCSATT radios
        if (productId) {
          $form.find(`input[name="convert_to_sub_${productId}"]`).prop('checked', false);
          $form.find(`input[name="convert_to_sub_${productId}"][value="0"]`).prop('checked', true);
        }

        // Reset dropdown field if it exists
        $form.find('select[name="convert_to_sub_dropdown"]').prop('selectedIndex', 0);

        $btn.prop('disabled', true).addClass('disabled');
      });
    });
  },
};
