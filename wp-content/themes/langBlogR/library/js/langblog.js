
/**
 * Main LangBlog App Script
 */

var LangBlogApp = (function($) {

    var toggle_menu = $('#nav');

    // Setup App Responsive Navigation
    var toggleNav = function() {
        if (toggle_menu.is(":visible")){
            toggle_menu.slideUp( 600, 'easeInOutExpo', function(){
                toggle_menu.css('display','');
            });
        } else {
            toggle_menu.slideDown( 600, 'easeOutExpo' );
        }
    };

    // Initialize application (public method)
    var init = function() {

        $('#nav-open-btn').on('click', function(e){
            e.preventDefault();
            toggleNav();
        });
        
        $('#nav-close-btn').on('click', function(e){
            e.preventDefault();
            toggleNav();
        });
    
    };

    return { // public
        init: init
    };

}(jQuery));

jQuery(document).ready(function() {
    LangBlogApp.init();
});