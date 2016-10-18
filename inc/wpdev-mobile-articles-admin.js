/* Switches.js */
!function(e){e.fn.extend({iosCheckbox:function(){"true"!==e(this).attr("data-ios-checkbox")&&(e(this).attr("data-ios-checkbox","true"),e(this).each(function(){var c=e(this),s=jQuery("<div>",{"class":"ios-ui-select"}).append(jQuery("<div>",{"class":"inner"}));c.is(":checked")&&s.addClass("checked"),c.hide().after(s),s.click(function(){s.toggleClass("checked"),s.hasClass("checked")?c.prop("checked",!0):c.prop("checked",!1)})}))}})}(jQuery);

jQuery(function($) {
    $(document).ready(function() {
      $('.wrap.wpdev-mobile-admin input[type=checkbox]').addClass('ios');
      $('table.form-table.amp-settings input[type=checkbox]').addClass('ios-amp');
      $('table.form-table.fbia-settings input[type=checkbox]').addClass('ios-fbia');
    });
});

// Activate Switches
jQuery(function($) {
    $(document).ready(function() {
       $(".ios").iosCheckbox();
    });
});

jQuery(function($) {
    $(document).ready(function() {
      $(".wpdev-mobile-admin form h2").first().addClass("amp-settings");
      $(".wpdev-mobile-admin form h2").last().addClass("fbia-settings");
      $(".wpdev-mobile-admin table.form-table").addClass("hidden");
      $(".wpdev-mobile-admin table.form-table").first().addClass("amp-settings");
      $(".wpdev-mobile-admin table.form-table").last().addClass("fbia-settings");
    });
    $("button.wpdev-button-amp").on("click", function(){
        $(".wpdev-mobile-admin table.form-table").first().toggleClass("hidden");
    });
    $("button.wpdev-button-fbia").on("click", function(){
        $(".wpdev-mobile-admin table.form-table").last().toggleClass("hidden");
    });
});
