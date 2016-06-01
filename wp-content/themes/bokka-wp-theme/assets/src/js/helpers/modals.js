$('.modal-trigger').on('click', function(event){
    event.preventDefault()
    modalContent = $(this).closest('.section').find('.modal-content').html()
    $('.modal-body-inner').html(modalContent)
    $('.modal').fadeIn()
})

$('.modal-close, .modal').not('.modal-body').on('click', function(event){
    event.preventDefault()
    if($(event.target).hasClass('modal-body'))
        return
    if($(event.target).closest('.modal-body').length && !$(event.target).hasClass('modal-close'))
        return

    $(this).closest('.modal').fadeOut()
})