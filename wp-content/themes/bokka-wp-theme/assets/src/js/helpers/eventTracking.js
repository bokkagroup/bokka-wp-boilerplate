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
    } else if (el.hasClass('floorplan-brand-window')) {
        el.on('click', '.button.modal-trigger', function(event){
            var text = el.find('.button').text().trim()
            label = ('FP-BRand-Window-'+text).replace(/(\r\n|\n|\r)/gm,"")//define our label
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
            label = (' Coming-soon-get-updates-'+text).replace(/(\r\n|\n|\r)/gm,"")//define our label
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
    );
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
