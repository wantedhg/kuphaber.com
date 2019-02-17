var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
    showLeftPush = document.getElementById( 'open-side-menu' ),
    body = document.body;

showLeftPush.onclick = function() {
    classie.toggle( this, 'active' );
    classie.toggle( body, 'cbp-spmenu-push-toright' );
    classie.toggle( menuLeft, 'cbp-spmenu-open' );
    disableOther( 'open-side-menu' );
};


function disableOther( button ) {

    if( button !== 'open-side-menu' ) {
        classie.toggle( showLeftPush, 'disabled' );
    }

}


$(document).ready(function(){
	$('#open-side-menu').click(function(){
		$(this).toggleClass('open');
	});
});

(function($){
    $(window).on("load",function(){
        $(".cbp-spmenu-vertical").mCustomScrollbar({
            theme:"light-2",
        });
    });
})(jQuery);