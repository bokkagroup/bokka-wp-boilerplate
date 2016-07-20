$('.modal-trigger').on('click', function(event){
    event.preventDefault()
    var parent = $(this).closest('.section')
    var modalId = $(this).data('modal') || false
    var modal
    if(modalId){
        modal = $('#' + $(this).data('modal'))
    } else {
        modal = parent.find('.modal')
    }

    modal.fadeIn()
})

$('.modal-close, .modal').not('.modal-body').not('a').on('click', function(event){

    if($(event.target).hasClass('modal-body'))
        return
    if($(event.target).closest('.modal-body').length && !$(event.target).hasClass('modal-close'))
        return

    event.preventDefault()
    $(this).closest('.modal').fadeOut()
})
