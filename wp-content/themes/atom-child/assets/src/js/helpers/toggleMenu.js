if ($('.post-grid .categories').length && window.matchMedia("(max-width: 767px)").matches) {
    $wrapper = $('.post-grid .categories');
    $toggle = $wrapper.find('.toggle');

    if ($toggle.length) {
        $menu = $wrapper.find('ul');

        // hide categories
        $menu.hide();

        $toggle.on('click', function(event) {
            $menu.toggle();
            $wrapper.toggleClass('open');
        });
    }
}
