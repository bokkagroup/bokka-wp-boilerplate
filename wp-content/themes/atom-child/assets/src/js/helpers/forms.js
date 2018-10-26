var CookieJS = require('../vendor/cookies')

$(window).load(function() {
    $('.gform_wrapper').find('label').each(
        function () {
            var text = $(this).clone()    //clone the element
                .children() //select all the children
                .remove()   //remove all the children
                .end()  //again go back to selected element
                .text().toLocaleLowerCase();
            var first_name = CookieJS.get('first_name')
            var last_name = CookieJS.get('last_name')
            var email = CookieJS.get('email')
            var phone = CookieJS.get('phone')

            if( text === 'name') {
                if(first_name !== undefined){
                    $(this).next('.ginput_container').find('.name_first input').val(first_name)
                }
                if(last_name !== undefined){
                    $(this).next('.ginput_container').find('.name_last input').val(last_name)
                }
            } else if( text === 'first name') {
                if(first_name !== undefined){
                    $(this).next('.ginput_container').find('input').val(first_name)
                }
            } else if( text === 'last name') {
                if(last_name !== undefined){
                    $(this).next('.ginput_container').find('input').val(last_name)
                }
            } else if (text === 'email') {
                if(email !== undefined){
                    $(this).next('.ginput_container').find('input').val(email)
                }
            } else if (text === 'phone') {
                if(email !== undefined){
                    $(this).next('.ginput_container').find('input').val(phone)
                }
            }
        }
    );
});


/**
 * This function listens for when a user is trying to submit a form and sets a cookie with first/last/email for later retrieval.
 */
$(document).on('click', ".gform_wrapper input[type=submit]", function (event) {
    $(this).closest('.gform_wrapper').find('label').each(
        function () {
            var input
            var text = $(this).clone()    //clone the element
                .children() //select all the children
                .remove()   //remove all the children
                .end()  //again go back to selected element
                .text().toLocaleLowerCase();

            if (text == 'name') {
                var names = []
                input = $(this).next('.ginput_container').find('input')

                input.each(function() {
                    input = $(this).val().split(' ')
                    names.push(input)
                })

                if (names[0]) {
                    CookieJS.set({
                        name: 'first_name',
                        value: names[0],
                        path: '/'
                    })
                }

                if(names[1]) {
                    CookieJS.set({
                        name: 'last_name',
                        value: names[1],
                        path: '/'
                    })
                }
            } else if( text == 'first name') {
                input = $(this).next().find('input').val()
                CookieJS.set({
                    name:   'first_name',
                    value:  input,
                    path: '/'
                })
            } else if (text == 'last name') {
                input = $(this).next().find('input').val()
                CookieJS.set({
                    name:   'last_name',
                    value:  input,
                    path: '/'
                })
            } else if (text == 'email') {
                input = $(this).next().find('input').val()
                CookieJS.set({
                    name:   'email',
                    value:  input,
                    path: '/'
                })
            } else if (text == 'phone') {
                input = $(this).next().find('input').val()
                CookieJS.set({
                    name:   'phone',
                    value:  input,
                    path: '/'
                })
            }
        }
    )
})
