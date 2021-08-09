(function($) {
	$.entwine('ss', function($) {
		$(".mce-edit-area iframe").entwine({
			onmatch: function() {
				$(this).attr("title","");
				$(function() { $( '[title]' ).tooltip({ content: function() { return $(this).attr('title'); } }); });
			}
		}),
		// can't see why onclick is not firing
		$(".grid-field .gridfield-button-duplicate").entwine({
			onmatch: function() {
				console.log('match');
			},
			onclick:function(e){
				console.log('click');
					
				if(!confirm('Are you sure you want to duplicate this record?')) {
					e.preventDefault();
					return false;
				} else {
					this._super(e);
				}
				
			}
		})
	});
})(jQuery);

