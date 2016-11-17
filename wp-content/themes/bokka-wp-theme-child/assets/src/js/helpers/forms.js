var productTypeData = $('.product-type-data .product-type');
var requestInfoForm = $('#request_info_form');
var hiddenInputField = requestInfoForm.find('.neighborhood-product-data');

if (productTypeData.length > 0 && requestInfoForm.length > 0) {
    var productDataInput = hiddenInputField.find('input[type=text]');

    if (productTypeData.length === 1) {
        productDataInput.val($(productTypeData[0]).text());
    } else {
        var productSelect  = '<li class="gfield checkbox-group product-types">';
            productSelect += '<label class="gfield_label">What type of home(s) are you interested in?</label>';
            productSelect += '<div class="ginput_container ginput_container_checkbox"><ul class="gfield_checkbox">';
            productTypeData.each(function(index) {
                var type = $(productTypeData[index]).text();
                var typeId = type.toLowerCase().replace(/ /g,"_");
                productSelect += '<li>';
                productSelect += '<input name="input_' + typeId + '" type="checkbox" value="' + type + '" id="choice_' + typeId + '">';
                productSelect += '<label for="choice_' + typeId + '" id="id_' + typeId + '">' + type + '</label>';
                productSelect += '</li>';
            });
            productSelect += '</ul></div>';
            productSelect += '</li>';

        $(productSelect).insertBefore(hiddenInputField.prev().prev());

        $('.gform_wrapper form input[type="submit"]').on('click', function(e) {
            e.preventDefault();

            var selectedProducts = $('.product-types').find('input[type=checkbox]:checked');
            var productValues = '';

            if (selectedProducts.length > 0) {
                selectedProducts.each(function(index) {
                    productValues += (index === 0) ? $(this).val() : ', ' + $(this).val();
                });
            }
            productDataInput.val(productValues);

            // Manually submit the form
            $('.gform_wrapper form').submit();
        });
    }
}

//Removes text from cloned text areas
if ($("body").hasClass('page-homeowner-resources')) {
    gfFilterFunction = "gform.addFilter( 'gform_list_item_pre_add', function ( clone ) {" +
    "    clone.find('td:eq(0) textarea').val('');" +
    "    return clone;" +
    "} );";

    var s = document.createElement("script");
    s.type = "text/javascript";
    s.text = gfFilterFunction;

    $("body").append(s);
}
