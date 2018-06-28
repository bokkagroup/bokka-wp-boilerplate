var queryString = require("query-string");

$(".facetwp-counts").bind("DOMSubtreeModified",countFloorplans);
/**
 * we need a counter that will count what facetWP is filtering and what our custom locations filter is filtering
 */
function countFloorplans()
{
    var hidden_count = 0;
    var fwpcount =  $('.facetwp-counts').first().text();
    $('.neighborhood').each(function(){
        if ($(this).css('display') == 'none') {
            hidden_count = hidden_count + $(this).find('.product-item').length;
        }
    });

    var count = fwpcount - hidden_count;
    $('.facet-counter').text(count);

    var label = count === 1 ? "Floorplan" : "Floorplans";
    $('.facet-counter-label').text(label);
}

$(document).on('facetwp-loaded', function() {
    var parsed = queryString.parse(location.search);
    if(parsed.location)
    {
        filterFloorplanLocation(parsed.location);
    }
});


$('.floorplans-location').on('change', function(event){
    var value = $(this).val();
    $('.facet-reset').css('display', 'block');
    filterFloorplanLocation(value);
});

/**
 * Filter alll of our neighborhoods
 * @param value
 */
function filterFloorplanLocation(value)
{
    //hide the neighborhoods
    $('.floorplans-list .neighborhood').each(function(){
        if(!value) {
            $(this).css('display', 'block');
        } else if(value != $(this).data('location')){
            $(this).css('display', 'none');
        } else {
            $(this).css('display', 'block');
        }
    });

    //update our url
        var parsed = queryString.parse(location.search);
        if(!value) {
            delete parsed.location;
        } else {
            parsed.location = value;
        }
        var stringified = queryString.stringify(parsed);
        history.pushState(null, null, location.protocol + '//' + location.host + location.pathname + '?' + stringified);


    $('.floorplans-location option[value='+value+']').prop('selected', true);
    //update our counter
    countFloorplans();
}


// reset both facet wp and the locations filter
$('.facet-reset button').on('click', function(event){

    //reset our dropdown
    $('.floorplans-location option:first-child').prop('selected', true);

    //show all our neighborhoods
    $('.floorplans-list .neighborhood').each(function(){
        $(this).css('display', 'block');
    });

    //make sure our Url doesnt have locations in it
    var parsed = queryString.parse(location.search);
    delete parsed.location;
    var stringified = queryString.stringify(parsed);
    history.replaceState(null, null, location.protocol + '//' + location.host + location.pathname + '?' + stringified);

    //reset facetwp
    FWP.reset();

});

$('.show-filters').on('click', function(){
    $(this).siblings('.wrapper').slideDown();

    $(this).fadeOut();
})

$(document).on('facetwp-refresh', function() {

    //should we see the reset button?
    var boolean = false;
    for (var key in FWP.facets) {
        if (FWP.facets.hasOwnProperty(key)) {
            if(FWP.facets[key].length > 0) {
                boolean = true;
            }
        }
    }

    if (boolean === true)
    {
        $('.facet-reset').css('display', 'block');
    } else {
        $('.facet-reset').css('display', 'none');
    }
});