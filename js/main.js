(function( $ ) {
    /*
    // Verify that the js file loads
    $( document ).ready(function() {
        console.log( "ready!" );
    });
    */
    // Add Color Picker to all inputs that have 'color-field' class
    $(function() {
        $('.color-field').wpColorPicker();
    });
     
})( jQuery );