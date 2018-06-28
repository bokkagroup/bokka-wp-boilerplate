

(function(){
    CookieJS = require('../vendor/cookies')
    require('../utility/urls')

    function setUTMCookies(){
        setUTMCookie("utm_source")
        setUTMCookie("utm_medium")
        setUTMCookie("utm_campaign")
    }

    function setUTMCookie(name){
        var queryValue = getParameterByName(name)
        var cookie = CookieJS.get("BOKKAWPTHEME_"+name)
        if (queryValue !== null && (cookie == undefined || cookie == 'null')) {
            CookieJS.set({
                name: "BOKKAWPTHEME_"+name,
                value: queryValue,
                path: "/",
            })
        }
    }

    setUTMCookies()

    var utmFields = $('.gfield.utm_source, .gfield.utm_medium, .gfield.utm_campaign')
    if( utmFields.length > 0){
        utmFields.each(function(){
            var $el = $(this).find('input')
            if($(this).hasClass('utm_source')){
                setgField('utm_source', $el)
            } else if($(this).hasClass('utm_medium')){
                setgField('utm_medium', $el)
            } else if($(this).hasClass('utm_campaign')){
                setgField('utm_campaign', $el)
            }
        })
    }

    function setgField(name, $el){
        var value = CookieJS.get("BOKKAWPTHEME_"+name)
        if (value !== undefined){
            $el.attr('value', value)
        }
    }
})($);