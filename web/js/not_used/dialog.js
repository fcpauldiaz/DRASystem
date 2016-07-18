/*global jQuery */

/*
 * Create modal dialogs.
 * The dialog plugin does not work on an element.
 *
 * Syntax
 * ===============
 *  $.dialog.open(name)
 *  $.dialog.close(name)
 *  $.dialog.alert(msg)
 *  $.dialog.confirm(msg[,options])
 *
 * Promise Methods
 * ===============
 *  .onOpen(function(element){})
 *  .onClose(function(element){})
 *  .onClose(function(boolean or text of optional button){}) - confirm only.
 *  .autoClose(msecs)
 *
 * Confirm Options
 * ===============
 *  ok: "Text of OK button"
 *  cancel: "Text of Cancel button"
 *  option: "Text of optional button" - goes to the left of the Cancel button
 *    The text of the button is returned in the onClose method if clicked.
 *
 * How it Works
 * ===============
 * It creates a div with the class of "blocker fade" at the end of the body.
 * It gets a file by name that ends in ".html" and adds it to the blocker div.
 * When shown it adds the "in" class to the blocker div. It then calls onOpen
 * function where you can add behavior to the dialog. The onClose method allows
 * you to remove any event handlers and respond to a confirm dialog.
 */

(function ($) {
  'use strict';

  // Globals
  var dialogs = {}, settings = {
    path: "web/",
    extension: ".html",
    replace: true,
    blocker: '<div class="blocker fade"></div>',
    confirm: {
      option_btn_start: '<button class="btn btn-warning option" type="button">',
      option_btn_end: '</button>',
      option_btn_class: 'option'
    }
  };

  // jQuery not defined.
  if (!$) {
    return;
  }

  $.dialog = (function () {
    /*
     * Close the dialog.
     */
    function close(name) {
      var dialogEl, dialog = dialogs[name].dialog;

      // If close has already been called, return.
      if (!dialog) {
        return;
      }

      // If there is a timeout, clear the timeout to stop it.
      if (dialog.timeout) {
        clearTimeout(dialog.timeout);
        dialog.timeout = null;
      }

      // Get the blocker element of the dialog.
      dialogEl = $('#' + name);

      // Hide the dialog.
      dialogEl.hide().removeClass("in");

      // Remove click handlers.
      dialogEl.find(".cancel, .ok, ." + settings.confirm.option_btn_class).off("click.dialog");

      // Remove html and all handlers if replace setting.
      dialogEl.empty();

      // Remove key handlers.
      $(document).off("keyup.dialog");
      $(document).off("keydown.dialog");

      // Run the close function.
      dialog.onClose(dialogEl);

      // Delete the open dialog object for this dialog.
      dialog = {};
    }

    /*
     * Check for the Esc key pressed. If pressed, close the dialog.
     */
    function checkEsc(name) {
      // Capture the Esc key to close the dialog.
      $(document).on("keyup.dialog", function (event) {
        if (event.keyCode === 27) {
          event.stopPropagation();
          close(name);
        }
      });
    }

    /*
     * Check for the Enter/Return key pressed. If pressed call callback function.
     */
    function checkEnter(name, callback) {
      // Capture the Enter key to close the dialog.
      $(document).on("keydown.dialog", function (event) {
        if (event.keyCode === 13) {
          event.preventDefault();
          event.stopPropagation();
          if (typeof callback === "function") {
            callback();
          }
          close(name);
        }
      });
    }

    /*
     * Show the dialog, add close behavior, and call any passed onOpen function.
     */
    function show(name) {
      var dialogEl = $('#' + name),
        dialog = dialogs[name].dialog;

      // Show the dialog.
      dialogEl.show().addClass("in");

      // Run onOpen function
      dialog.onOpen(dialogEl);

      // Check for auto close of dialog.
      dialog.autoClose();

      // Close on Esc key.
      checkEsc(name);

      // Close on Cancel button.
      dialogEl.find(".cancel, .ok, ." + settings.confirm.option_btn_class).on("click.dialog", function () {
        close(name);
      });
    }

    /*
     * Get dialog html from the server and add it to the DOM.
     */
    function get(name) {
      // Create background blocker and add id.
      var blocker = $(settings.blocker).attr("id", name);
     
      if (name == 'confirm') {
        var insertHtml = $('.confirm')[0].outerHTML;  
      }
      else if (name == 'alert') {
        var insertHtml = $('.alert')[0].outerHTML;
      }
      

      dialogs[name].html = insertHtml;
      // Add dialog html to blocker.
      blocker.append(insertHtml);
      // Add blocker to end of body.
      $("body").append(blocker);
      show(name);
    
    }

    /*
     * Open the dialog.
     */
    function open() {
      // If the dialog html has been added to the DOM, show the dialog, otherwise
      // get the dialog html from the server and add it to the DOM and show the
      // dialog.

      var i, a, name, dialog, onOpen, onClose, autoClose;

      // Process variable number of arguments.
      for (i = 0; i < arguments.length; i++) {
        a = arguments[i];
        if (typeof a === "string") {
          name = a;
        } else if (typeof a === "function") {
          if (!onOpen) {
            onOpen = a;
          } else {
            onClose = a;
          }
        } else if (typeof a === "number") {
          autoClose = a;
        }
      }

      // Create dialog object for dialog.
      if (!dialogs[name]) {
        dialogs[name] = {dialog: {}};
      } else if (settings.replace) {
        // Replace the html of the dialog each time it is displayed.
        $('#' + name).html(dialogs[name].html);
      }

      dialog = dialogs[name].dialog;

      // Allow the dialog object to be auto closed.
      dialog.autoClose = function (msecs) {
        msecs = msecs || autoClose;
        if (typeof msecs === "number" && !dialog.timeout) {
          this.timeout = setTimeout(function () {
            dialogs[name].dialog.timeout = null;
            close(name);
          }, msecs);
        }
        return dialog;
      };

      dialog.onOpen = function (fn) {
        var dialogEl, origFn;
        if (typeof fn === "function") {
          dialogEl = $('#' + name);
          if (dialogEl.length) {
            fn(dialogEl);
          } else if (onOpen) {
            origFn = onOpen;
            this.onOpen = function (el) {
              origFn(el);
              fn(el);
            };
          } else {
            onOpen = fn;
          }
        } else if (fn && fn.jquery && typeof onOpen === "function") {
          // Was passed a jQuery object, call the onOpen function.
          onOpen(fn);
        }
        return dialog;
      };

      dialog.onClose = function (fn) {
        var origFn;
        if (typeof fn === "function") {
          if (this.onClose) {
            origFn = this.onClose;
            this.onClose = function (el) {
              origFn(el);
              fn(el);
            };
          } else {
            this.onClose = fn;
          }
        } else if (fn && fn.jquery && typeof onClose === "function") {
          onClose(fn);
        }
        return dialog;
      };

      // Is the dialog already in the DOM?
      if ($('#' + name).length) {
        show(name);
      } else {
        console.log(name);
        get(name);
      }

      return dialog;
    }

    /*
     * Display an alert message with an OK button.
     */
    function alert() {
      var a, msg, onClose, autoClose, i;

      // Process variable number of arguments.
      for (i = 0; i < arguments.length; i++) {
        a = arguments[i];
        if (typeof a === "string") {
          msg = a;
        } else if (typeof a === "function") {
          onClose = a;
        } else if (typeof a === "number") {
          autoClose = a;
        }
      }

      function onOpen(dialogEl) {
        // Add the alert message to the alert dialog. If wider than 320 pixels change new lines to <br>.
        dialogEl.focus().find(".msg").html(msg.replace(/\n/g, (document.body.offsetWidth > 320 ? "<br>" : " ")));
        checkEnter("alert");
      }

      return open("alert", onOpen, onClose, autoClose);
    }

    /*
     * Display a dialog with a message and cancel and ok buttons.
     * If the user clicks the OK button, pass true to the callback
     * function. Otherwise pass false to the callback function.
     */
    function confirm() {
      var bReturn = false, a, msg, i, options = {
        ok: undefined,
        cancel: undefined,
        option: undefined,
        onOpen: undefined,
        onClose: undefined
      };

      // Process variable number of arguments.
      for (i = 0; i < arguments.length; i++) {
        a = arguments[i];
        if (typeof a === "string") {
          if (!msg) {
            msg = a;
          } else if (!options.ok) {
            options.ok = a;
          } else {
            options.cancel = a;
          }
        } else if (typeof a === "function") {
          options.onClose = a;
        } else {
          options = $.extend(options, a);
        }
      }

      function onOpen(dialogEl) {
        var dialog_ok_btn = dialogEl.find(".ok"), dialog_cancel_btn = dialogEl.find(".cancel"), dialog_option_btn;

        // Set OK button text.
        if (options.ok) {
          dialog_ok_btn.text(options.ok);
        } else {
          dialog_ok_btn.text(dialog_ok_btn.attr("data-default"));
        }

        // Set Cancel button text.
        if (options.cancel) {
          dialog_cancel_btn.text(options.cancel);
        } else {
          dialog_cancel_btn.text(dialog_cancel_btn.attr("data-default"));
        }

        // Change the message. If wider than 320 pixels change new lines to <br>.
        dialogEl.focus().find(".msg").html(msg.replace(/\n/g, (document.body.offsetWidth > 320 ? "<br>" : " ")));

        // If the user clicks ok, set the return value.
        dialogEl.find(".ok").on("click.confirm", function () {
          bReturn = true;
        });

        // Add option button if requested.
        if (options.option) {
          // Create option button.
          dialog_option_btn = $(settings.confirm.option_btn_start + options.option + settings.confirm.option_btn_end);
          // Add it to the form.
          dialog_cancel_btn.before(dialog_option_btn);
          // If the user clicks ok, set the return value to the option button text.
          dialog_option_btn.on("click.confirm", function (event) {
            event.stopPropagation();
            // Return the text of the button.
            bReturn = options.option;
          });
        }

        // If the user presses the Enter key, set the return value as if they
        // clicked the OK button.
        checkEnter("confirm", function () {
          bReturn = true;
        });

        if (typeof options.onOpen === "function") {
          options.onOpen(dialogEl);
        }

        return dialogs.confirm.dialog;
      }

      function onClose(dialogEl) {
        if (typeof dialogEl === "function") {
          options.onClose = dialogEl;
          return dialogs.confirm.dialog;
        }

        // remove click handlers and option button.
        dialogEl.find(".ok, ." + settings.confirm.option_btn_class).off("click.confirm");
        if (options.option && !settings.replace) {
          dialogEl.find(settings.confirm.option_btn_class).remove();
        }

        // Call the callback function with a return value.
        if (typeof options.onClose === "function") {
          options.onClose(bReturn);
        }
        return dialogs.confirm.dialog;
      }

      // Overwrite onClose method with confirm onClose method.
      open("confirm", onOpen).onClose = onClose;
      return dialogs.confirm.dialog;
    }

    /*
     * Change path and extension settings.
     */
    function set(obj) {
      settings = $.extend(true, settings, obj);
    }

    /*
     * Helper functions to determine the type of value passed by confirm.onClose.
     */
    function isOk(val) {
      return typeof val === "boolean" && val;
    }

    function isCancel(val) {
      return typeof val === "boolean" && !val;
    }

    function isOption(val) {
      return typeof val === "string" && val ? true : false;
    }

    // Return public methods.
    return {
      open: open,
      close: close,
      alert: alert,
      confirm: confirm,
      set: set,
      isOk: isOk,
      isCancel: isCancel,
      isOption: isOption
    };
  }());
}(jQuery));
