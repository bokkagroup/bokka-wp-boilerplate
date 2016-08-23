/**
 * Organism Events
 */
$('.section').each(function(){
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
    }

    /**
     * SETUP our labels & actiosn and call the Event tracking function
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

    //floorplan brandwindow
    } else if (el.hasClass('detail-brand-window') && el.hasClass('floorplan')) {
        el.on('click', '.button.modal-trigger', function(event){
            var text = el.find('.button').text().trim()
            label = ('FP-Brand-Window-'+text).replace(/(\r\n|\n|\r)/gm,"")//define our label
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

    }
})

function eventTrack(category, action, label){
    ga(
        'send',
        'event',
        category,
        action,
        label
    )
}

/**
 * Virtual Page Views for Gravity forms
 */
$(document).on("gform_confirmation_loaded", function(event, formId){
    var page = ''
    if(formId === 1){
        page = '/get-updates/thank-you'
    } else if(formId === 2){
        page = '/floorplan-get-more-info/thank-you'
    } else if(formId === 3){
        page = '/coming-soon-get-updates/thank-you'
    }
    ga('send', { hitType: 'pageview', page: page })
})
