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
