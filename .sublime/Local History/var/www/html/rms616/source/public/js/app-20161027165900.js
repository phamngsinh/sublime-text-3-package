jQuery(document).ready(function() {
    jQuery('.dataTables_paginate .pagination li').first().find('a').remove();
    jQuery('.dataTables_paginate .pagination li').first().append('<a href="#"><i class="fa fa-angle-left size-16" aria-hidden="true"></i></a>');
    jQuery('.dataTables_paginate .pagination li').last().find('a').remove();
    jQuery('.dataTables_paginate .pagination li').last().append('<a href="#"><i class=" size-16 fa fa-angle-right" aria-hidden="true"></i></a>');
    setTimeout(function() {
        //get max-height block-items
        setTimeout(function() {
            jQuery('.block-items').each(function(index, el) {
                var maxHeight = 0;
                var elementHeights = jQuery(this).find('.item').map(function() {
                    return jQuery(this).height();
                }).get();
                maxHeight = Math.max.apply(null, elementHeights);
                if (jQuery(window).width() > 767) {
                    jQuery(this).find('.item').height(maxHeight);
                }
            });
        }, 500);

        jQuery(window).resize(function() {
            document.body.style.overflow = "hidden";
            var windowWidth = jQuery(window).width();
            document.body.style.overflow = "";
            jQuery('.block-items').each(function(index, el) {
                jQuery(this).find('.item').css("height", 'auto');
            });
            jQuery('.block-items').each(function(index, el) {
                var maxHeight = 0;
                var elementHeights = jQuery(this).find('.item').map(function() {
                    return jQuery(this).height();
                }).get();
                maxHeight = Math.max.apply(null, elementHeights);
                if (jQuery(window).width() > 767) {
                    jQuery(this).find('.item').height(maxHeight);
                }
            });
        });
    });

    $("#edit-name").click(function() {
        jQuery(this).parent().find('input').attr("readonly", false);
        jQuery(this).parent().find('a').css('display', 'none');
    });
    $(".crop-img").imgLiquid();
    jQuery("#owl-demo").owlCarousel({
        autoPlay: false, //Set AutoPlay to 3 second
        items: 4,
        itemsDesktop: [1199, 4],
        itemsDesktopSmall: [979, 3],
        itemsTablet: [768, 3],
        itemsMobile: [600, 2],
        width: 1000,
        responsiveClass: true,
    });
    jQuery(function() {
        jQuery('#tablist li').click(function(e) {
            e.preventDefault();
            jQuery(this).tab('show');
        });
        //button next click
        jQuery('#buttonNext').click(function(e) {
            e.preventDefault();
            jQuery('#on-going').addClass('in active');
            jQuery('#tablist .next').addClass('active');
            jQuery('#tablist .back').removeClass('active');
            jQuery('#initial').removeClass('in active');
        });
        jQuery('#buttonBack').click(function(e) {
            e.preventDefault();
            jQuery('#initial').addClass('in active');
            jQuery('#on-going').removeClass('in active');
            jQuery('#tablist .next').removeClass('active');
            jQuery('#tablist .back').addClass('active');
        });

    });

    //buy new package
    $('#subscription_package').change(function() {
        var _index = $(this).val();
        $.get(baseUrl + '/subscriptions/' + _index, function(data) {
            $('input#subscription_package_expired').val(data.expire);
            $('input#subscription_package_id').val(_index);
            $('input.subscription_package_expired').val(data.expire);
            $('input#subscription_package_amount').val(data.price);
            $('span.subscription_package_amount_total').text('$' + data.price);
            $('input.subscription_package_amount').val(data.amount);

        });
    }).change(); // set initial textbox value on page load

    $('#subscription_package_upgrade').change(function() {
        var _index = $(this).val();
        $.get(baseUrl + '/subscriptions/upgrade-calculate/' + _index, function(data) {

            $('#subscription_package_summary').text(data.name);
            $('#subscription_package_price_summary').text(data.amount);
            $('#subscription_package_unused_summary').text(data.renderCreditUnused);
            $('#subscription_package_total_summary').text(data.renderAmountDue);
            $('#subscription_package_expired').val(data.expired);
            $('#subscription_package_id').val(_index);
            $('#subscription_package_amount').val(data.price);
            $('#subscription_package_due_amount').val(data.amountDue);
            $('.subscription_package_amount_due_total').html(data.renderTotal);


        });
    }).change(); // set initial textbox value on page load

    //next back at new order
    //button next click
    jQuery('#buy_next').click(function(e) {
        e.preventDefault();
        jQuery('#step2').addClass('in active');
        jQuery('#step1').removeClass('in active');

    });
    jQuery('#buy_back').click(function(e) {
        e.preventDefault();
        jQuery('#step1').addClass('in active');
        jQuery('#step2').removeClass('in active');
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //ajax validation form
    $("#ajaxPasswordForm").validate({
        rules: {
            old_password: {
                required: true,
                remote: {
                    url: baseUrl + "/ajax/auth/validate-password",
                    type: "post",
                }
            },
            password: {
                required: true,
                minlength: 3
            },
            password_confirm: {
                required: true,
                equalTo: "#password"
            },
        },
        messages: {
            old_password: {
              remote :"Your old password is incorrect."       
            },
            password_confirm: {
                equalTo: 'Does not match with new password.'
            }
        }
    });

});