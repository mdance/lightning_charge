window.EVENT_LIGHTNING_CHARGE_PAYMENT = 'lightning_charge.payment';

(function ($, Drupal) {
  'use strict';

  var ns = 'lightning_charge';

  Drupal.behaviors[ns] = {
    attach: function (context, settings) {
      var $window, subsettings;

      $window = $(window);
      subsettings = settings[ns];

      $window.once(ns).each(
        function() {
          var listen, url, connection;

          listen = subsettings['websocket'];

          if (listen) {
            url = subsettings['websocket_url'];

            connection = new WebSocket(url);
          } else {
            listen = subsettings['payment_stream'];

            if (listen) {
              url = subsettings['payment_stream_url'];

              connection = new EventSource(url);
            }
          }

          if (connection) {
            function paymentHandler(message) {
              var data, event;

              data = message.data;
              data = JSON.parse(data);

              event = new CustomEvent(
                EVENT_LIGHTNING_CHARGE_PAYMENT,
                {
                  detail: {
                    invoice: data
                  }
                }
              );

              window.dispatchEvent(event);
            }

            connection.addEventListener('message', paymentHandler);
          }

          window.addEventListener(
            EVENT_LIGHTNING_CHARGE_PAYMENT,
            function (event) {
              var url, selector, event_name, $target, invoice, ajax;

              invoice = event.detail.invoice;

              // console.log('Received event: ', invoice);

              url = subsettings.js_url;
              selector = subsettings.selector;
              event_name = subsettings.event;

              $target = $('#' + selector);

              $target.each(
                function(delta, element) {
                  var settings, parent, parent_success;

                  settings = {
                    base: selector,
                    element: element,
                    url: url,
                    event: event_name,
                    progress: false
                  };

                  ajax = new Drupal.ajax(settings);

                  parent = ajax.beforeSerialize;
                  parent_success = ajax.success.bind(ajax);

                  ajax.beforeSerialize = function (element, options) {
                    parent(element, options);

                    options.data['invoice'] = invoice;
                  }

                  ajax.success = function (response, status) {
                    //console.log(invoice);
                    //console.log(response);
                    parent_success(response, status);

                    delete Drupal.ajax.instances[ajax.instanceIndex];
                    $target.unbind(event_name);
                  }

                  Drupal.ajax[selector] = ajax;

                  $target.trigger(event_name);
                }
              );
            }
          );
        }
      );
    }
  }
})(jQuery, Drupal, drupalSettings);
