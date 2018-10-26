/**
 * Organism Events
 */
$('.section').each(function(){
    // Get eventTrack function from global window.bokka object
    var eventTrack = window.bokka.eventTrack;

    var el = $(this)
    var body = $('body')
    var category = ''
    var action = 'Click'
    var label = ''

    //set Category (page)
    if(body.hasClass('home')){
        category = 'Homepage'
    }if(body.hasClass('single-plans')){
        category = 'Floorplan Detail'
    } if(body.hasClass('single-model')){
        category = 'Model Detail'
    } if(body.hasClass('single-home')){
        category = 'Home Detail'
    } if(body.hasClass('single-communities') || body.hasClass('single-neighborhoods')) {
        category = 'Neighborhood Detail'
    } if(body.hasClass('page-our-neighborhoods')) {
        category = 'Neighborhood Overview'
    } if(body.hasClass('ask-a-question')) {
        category = 'Contact Page'
    }

    /**
     * SETUP our labels & actions and call the Event tracking function
     */

    //brand window organism
    if (el.hasClass('brand-window')) {
        el.on('click', '.button', function(event){
            var title = el.find('h1').text().trim()
            var text = el.find('.button').text().trim()
            label = ('Brand Window-'+title+'-'+text).replace(/(\r\n|\n|\r)/gm,"")//define our label
            eventTrack(category, action, label)
        })

    //cta-w-image
    } else if (el.hasClass('cta-w-image')) {
        el.on('click', '.button', function(event){
            var title = el.find('.title').text().trim()
            var text = el.find('.button').text().trim()
            label = ('CTA w/ Image-'+title+'-'+text).replace(/(\r\n|\n|\r)/gm,"")//define our label
            eventTrack(category, action, label) //define our label
        })

    // wysiwyg-block -- tel link/OSC
    } else if (el.hasClass('wysiwyg-block')) {
        el.on('click', 'a[href^=tel]', function(event) {
            label = ('Click to call-OSC Phone Number')
            eventTrack(category, action, label)
        });

    // form-basic -- gform submit
    } else if (el.hasClass('form-basic')) {
        el.on('click', 'input[type=submit].gform_button', function(event) {
            label = ('Contact Us Form-Contact Us')
            eventTrack(category, action, label)
        });

    // contact-us-modules
    } else if (el.hasClass('contact-us-modules')) {
        el.on('click', '.sales-offices a', function(event) {
            label = ('Contact Us Modules-Sales Offices-View All')
            eventTrack(category, action, label)
        });

    //cta-w-gallery
    } else if (el.hasClass('cta-w-gallery')) {
        el.on('click', '.button', function(event){
            var title = el.find('.title').text().trim()
            var text = el.find('.button').text().trim()
            label = ('CTA w/ Gallery-'+title+'-'+text).replace(/(\r\n|\n|\r)/gm,"")//define our label
            eventTrack(category, action, label)
        })

    //featured-slider
    } else if (el.hasClass('cards')) {
        el.find('.card').each(function(){
            var card = $(this)
            card.on('click', 'a', function(event){
                var title = card.find('.title').text().trim()
                var text = card.find('.button').text().trim()
                label = ('Cards-'+title+'-'+text).replace(/(\r\n|\n|\r)/gm,"")//define our label
                eventTrack(category, action, label)
            })
        })

        //testimonial
    } else if (el.hasClass('featured-slider')) {
        el.find('.slide').each(function(){
            var card = $(this)
            card.on('click', 'a', function(event){
                var title = card.find('.title').text().trim()
                var text = card.find('.button').text().trim()
                label = ('Feature Slider-'+title+'-'+text).replace(/(\r\n|\n|\r)/gm,"")//define our label
                eventTrack(category, action, label)
            })
        })

    //icon-grid
    } else if (el.hasClass('icon-grid')) {
        el.find('li').each(function(){
            var card = $(this)
            card.on('click', 'a', function(event){
                var text = card.find('.text').text().trim()
                label = ('Icon Grid-'+text).replace(/(\r\n|\n|\r)/gm,"")//define our label
                eventTrack(category, action, label)
            })
        })

    //cta-get-updates
    } else if (el.hasClass('cta-get-updates')) {
        el.on('click', 'a', function(event) {
            label = ('CTA-Get Updates')
            eventTrack(category, action, label)
        });

    //floorplan brandwindow
    } else if (el.hasClass('detail-brand-window') && el.hasClass('floorplan')) {
        el.on('click', '.button.fancy-trigger', function(event){
            var button = $(this);
            var target = button.attr('href');
            // if user has already provided their info, trigger virtual pageview when 'get brochure' modal is opened
            if (target == '#get_brochure_modal') {
                var neighborhood = '';
                var floorplan = '';
                var pathArray = window.location.pathname.split('/');
                if (pathArray.length) {
                    neighborhood = pathArray[3];
                    floorplan = pathArray[5];
                }
                page = '/thank-you/floorplan-detail-page/get-brochure/' + neighborhood + '/' + floorplan;
                ga('send', { hitType: 'pageview', page: page })
            } else {
                // otherwise just track click on CTA
                var text = button.text().trim();
                label = ('FP-Brand-Window-'+text).replace(/(\r\n|\n|\r)/gm,"")
                eventTrack(category, action, label)
            }
        })

    //custom cta w modal form
    } else if (el.hasClass('custom-cta-w-modal-form')) {
        el.on('click', '.button.modal-trigger', function(event){
            var text = el.find('.button').text().trim()
            label = ('Custom-CTA-w-Modal-Form-'+text).replace(/(\r\n|\n|\r)/gm,"")//define our label
            eventTrack(category, action, label)
        })

    //model brandwindow
    } else if (el.hasClass('detail-brand-window') && el.hasClass('model')) {
        el.on('click', '.button.modal-trigger', function(event){
            var text = el.find('.button').text().trim()
            label = ('Model-Brand-Window-'+text).replace(/(\r\n|\n|\r)/gm,"")//define our label
            eventTrack(category, action, label)
        })

    //tab gallery
    } else if (el.hasClass('tab-gallery')) {
        el.on('click', '.tab', function(event){
            var text = $(this).find('a').text().trim()
            label = ('Tab-Gallery-with-Button-'+text).replace(/(\r\n|\n|\r)/gm,"")//define our label
            eventTrack(category, action, label)
        })
        el.on('click', '.button', function(event){
            var text = el.find('.button').text().trim()
            label = ('Tab-Gallery-with-Button-'+text).replace(/(\r\n|\n|\r)/gm,"")//define our label
            eventTrack(category, action, label)
        })

    // neighborhood overview page
    } else if (el.hasClass('neighborhood-overview')) {
        el.on('click', '.tab', function(event) {
            var text = $(this).find('a').text().trim()

            if (text == 'Model Homes') {
                label = 'Listing Column-Floorplans'
            }
            if (text == 'Neighborhoods') {
                label = 'Listing Column-Neighborhoods'
            }
            if (text == 'Quick Move-in') {
                label = 'Listing Column-Quick Move Ins'
            }

            eventTrack(category, action, label)
        });
        el.on('click', '.button.more-details', function(event) {
            var text = $(this).text().trim();
            var context = $(this).closest('.item').find('.header .title h4').text().trim();
            label = ('Listing Column-'+context+'-'+text).replace(/(\r\n|\n|\r)/gm,"")//define our label
            eventTrack(category, action, label)
        })

    //comingsoon get updates
    } else if (el.hasClass('coming-soon-get-updates')) {
        el.on('click', '.button', function(event){
            var text = el.find('.button').text().trim()
            label = ('Coming-soon-get-updates-'+text).replace(/(\r\n|\n|\r)/gm,"")//define our label
            eventTrack(category, action, label)
        })
    } else if (el.hasClass('map-with-info')) {
        el.on('click', '.phone', function(event){
            var text = $(this).text().trim()
            label = ('Map-With-Info-Phone-'+text).replace(/(\r\n|\n|\r)/gm,"")//define our label
            eventTrack(category, action, label)
        })
        el.on('click', '.directions', function(event){
            var text = $(this).text().trim()
            label = ('Map-With-Info-Get-Directions').replace(/(\r\n|\n|\r)/gm,"")//define our label
            eventTrack(category, action, label)
        })
    } else if (el.hasClass('osc-request-info')) {
        el.on('click', 'a', function(event){
            if (category == 'Home Detail' || category == 'Floorplan Detail' || category == 'Model Detail') {
                label = 'OSC-Product-Detail-Curious-Get-In-Touch'//define our label
            } else if (category == 'Neighborhood Detail') {
                var h1 = $('h1').text()
                label = ('OSC Get Info-'+h1.trim()).replace(/(\r\n|\n|\r)/gm,"")
            } else {
                label = 'OSC Get Info'
            }
            eventTrack(category, action, label)
        })
    } else if (el.hasClass('neighborhood-brandwindow')){

        el.on('click', '.button', function(event){

            var text = $(this).text().trim()

            if (text == 'See Homes'){
                label = 'Neighborhood Brand Window-See Homes'
            }
            if(text == 'Get Updates'){
                label = 'Neighborhood Brand Window-Get Updates'
            }
            if(text == 'Get in Touch'){
                label = 'Neighborhood Brand Window-Get in Touch'
            }
            if(text == 'View Location'){
                label = 'Neighborhood Brand Window-View Location'
            }
            if(text == 'Sign Up For Updates Now'){
                label = 'Neighborhood Brand Window-Sign Up For Updates Now'
            }
            eventTrack(category, action, label)
        })
    } else if (category == 'Neighborhood Detail' && el.hasClass('alternating-content')){

        el.on('click', '.button', function(event){

            var text = $(this).text().trim()
            label = ("Neigh Info w/ Amenities-"+text).replace(/(\r\n|\n|\r)/gm,"")
            eventTrack(category, action, label)
        })
    } else if (category == 'Neighborhood Detail' && el.hasClass('site-map')){
        el.on('click', 'a', function(event){
            var text = $(this).text().trim()
            label = "Neigh Info + Amenities-View Homesite Map"
            eventTrack(category, action, label)
        })
    } else if (el.hasClass('tabbed-product-listings')){
        el.on('click', '.product-item', function(event){
            var item = $(this)
            var name = $(this).find('.title').clone()//clone the element
                .children() //select all the children
                .remove()   //remove all the children
                .end()  //again go back to selected element
                .text().trim();
            if(item.hasClass('plans')){
                label = 'Tabbed Product-Floorplan '+ name
            } else if (item.hasClass('home')){
                label = 'Tabbed Product Display-Quick Move In Home '+ name
            } else if (item.hasClass('model')){
                label = 'Tabbed Product Display-Model '+ name
            } else {
                label = 'Tabbed Product Display-Product'
            }

            eventTrack(category, action, label)
        })
    // form w/ text
    } else if (el.hasClass('form-w-text')) {
        el.on('click', '.button', function(event){
            var text = el.find('.button').text().trim()
            label = ('Form-With-Text-'+text).replace(/(\r\n|\n|\r)/gm,"")
            eventTrack(category, action, label)
        })
    }
})

/**
 * Floorplan overview events
 */
if ($('.floorplans-list').length) {
    var category = 'Floorplan Overview'
    var action = 'Click'

    var eventTrack = window.bokka.eventTrack
    var plans = $('.product-item')

    plans.each(function() {
        $(this).on('click', function() {
            var planName = $(this).find('.title:first-child').text().trim()
            var neighborhood = $(this).parents('.neighborhood').data('neighborhood')
            var label = 'Floorplan-' + neighborhood + '-' + planName
            eventTrack(category, action, label)
        })
    })
}

/**
 * Virtual Page Views for Gravity forms
 */
$(document).on("gform_confirmation_loaded", function(event, formId){
    var page = '';
    var body = $('body');

    if (formId === 1 && body.hasClass('home')){
        page = '/thank-you/homepage/get-updates'
    } else if (formId === 1){
        page = '/thank-you/get-updates'
    } else if(formId === 2 && body.hasClass('single-plans')){
        page = '/thank-you/floorplan-detail-page/get-more-info'
    } else if(formId === 2 && body.hasClass('single-model')){
        page = '/thank-you/model-detail-page/get-more-info'
    } else if(formId === 2 && body.hasClass('single-home')){
        page = '/thank-you/QMI-detail-page/get-more-info'
    } else if(formId === 2){
        page = '/thank-you/get-more-info'
    } else if(formId === 3 && body.hasClass('single-plans')){
        page = '/thank-you/floorplan-detail-page/coming-soon-get-updates'
    } else if(formId === 3 && body.hasClass('single-model')){
        page = '/thank-you/model-detail-page/coming-soon-get-updates'
    } else if(formId === 3 && body.hasClass('single-home')){
        page = '/thank-you/QMI-detail-page/coming-soon-get-updates'
    } else if(formId === 3){
        page = '/thank-you/coming-soon-get-updates'
    } else if(formId === 6 || formId === 42){
        var neighborhood = '';
        var pathArray = window.location.pathname.split('/');
        if (pathArray.length) {
            neighborhood = pathArray[3];
        }
        page = '/thank-you/neighborhood-detail-page/request-info/' + neighborhood
    } else if(formId === 9){
        page = '/thank-you/contact-us-page/ask-a-question'
    } else if(formId === 10){
        page = '/homeowner-resources-page/thank-you'
    } else if(formId === 26){
        page = '/weecottage.blog/thank-you'
    } else if(formId === 38){
        var neighborhood = '';
        var floorplan = '';
        var pathArray = window.location.pathname.split('/');
        if (pathArray.length) {
            neighborhood = pathArray[3];
            floorplan = pathArray[5];
        }
        page = '/thank-you/floorplan-detail-page/get-brochure/' + neighborhood + '/' + floorplan
    } else if(formId === 37){
        var neighborhood = '';
        var pathArray = window.location.pathname.split('/');
        if (pathArray.length) {
            neighborhood = pathArray[3];
        }
        page = '/thank-you/neighborhood-detail-page/modal-gallery/' + neighborhood
    } else if(formId === 40){
        page = '/thank-you/home-collections/get-more-info'
    }
    ga('send', { hitType: 'pageview', page: page })
})
