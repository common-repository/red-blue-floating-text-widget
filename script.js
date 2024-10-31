$.fn.reverse = function() {
    return this.pushStack(this.get().reverse(), arguments);
};

// create two new functions: prevALL and nextALL. they're very similar, hence this style.
$.each( ['prev', 'next'], function(unusedIndex, name) {
    $.fn[ name + 'ALL' ] = function(matchExpr) {
        // get all the elements in the body, including the body.
        var $all = $('body').find('*').andSelf();

        // slice the $all object according to which way we're looking
        $all = (name == 'prev')
             ? $all.slice(0, $all.index(this)).reverse()
             : $all.slice($all.index(this) + 1)
        ;
        // filter the matches if specified
        if (matchExpr) $all = $all.filter(matchExpr);
        return $all;
    };
});

jQuery(window).load(function(){
  jQuery(function() {
		var a = function() {
			var b = jQuery(window).scrollTop();
			var d = jQuery("#redbluefloater-anchor").offset().top;
			var c = jQuery("#redbluefloater");
			var w = jQuery("#redbluefloater").width();
			if (b>d) {
				c.css({position:"fixed",top:"0px", width: w});
			} else if (b<=d) {
				c.css({position:"relative",top:"", width: "100%"});
			}
		};
		jQuery(window).scroll(a);
	});
});