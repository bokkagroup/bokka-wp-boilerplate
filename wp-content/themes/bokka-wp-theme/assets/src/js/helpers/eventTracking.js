/**
 * Organism Events
 */
$('.section').each(function(){
    var el = $(this)
    var category = ''
    var action = 'Click'
    var label = ''

    //brand window organism
    if (el.hasClass('brand-window')) {
        el.on('click', '.button', function(event){
            var title = el.find('h1').text().trim()
            var text = el.find('.button').text().trim()
            label = ('Brand Window-'+title+'-'+text).replace(/(\r\n|\n|\r)/gm,"")
            eventTrack(category, action, label)
        })

    //cta-w-image
    } else if (el.hasClass('cta-w-image')) {
        el.on('click', '.button', function(event){
            var title = el.find('.title').text().trim()
            var text = el.find('.button').text().trim()
            label = ('CTA w/ Image-'+title+'-'+text).replace(/(\r\n|\n|\r)/gm,"")
            eventTrack(category, action, label)
        })

    //cta-w-gallery
    } else if (el.hasClass('cta-w-gallery')) {
        el.on('click', '.button', function(event){
            var title = el.find('.title').text().trim()
            var text = el.find('.button').text().trim()
            label = ('CTA w/ Gallery-'+title+'-'+text).replace(/(\r\n|\n|\r)/gm,"")
            eventTrack(category, action, label)
        })

    //featured-slider
    } else if (el.hasClass('cards')) {
        el.find('.card').each(function(){
            var card = $(this)
            card.on('click', 'a', function(event){
                var title = card.find('.title').text().trim()
                var text = card.find('.button').text().trim()
                label = ('Cards-'+title+'-'+text).replace(/(\r\n|\n|\r)/gm,"")
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
                label = ('Feature Slider-'+title+'-'+text).replace(/(\r\n|\n|\r)/gm,"")
                eventTrack(category, action, label)
            })
        })

    //icon-grid
    } else if (el.hasClass('icon-grid')) {
        el.find('li').each(function(){
            var card = $(this)
            card.on('click', 'a', function(event){
                var text = card.find('.text').text().trim()
                label = ('Icon Grid-'+text).replace(/(\r\n|\n|\r)/gm,"")
                eventTrack(category, action, label)
            })
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
        page = '/thank-you/get-updates'
    }
    ga('send', { hitType: 'pageview', page: page })
})
