"use strict";
var panel = {
	init: function(){
		var module = this;
		$('select.jumpto', document).change(function(){
			var subject = $(this);
			console.info(filter.filter);
			switch(filter.filter){
				case 'weekly':
					module.doJumpToWeek(subject);
					break;
				case 'monthly':
					module.doJumpToMonth(subject);
					break;
				case 'yearly':
					module.doJumpToYear(subject);
					break;
			}
		});
	},
	jumpTo: function(){
		location.href = 'panel.php?id=' + filter.id + '&filter=' + filter.filter + '&offset=' + arguments[0];
	},
	doJumpToWeek: function(){
		
	},
	doJumpToMonth: function(){
		
	},
	doJumpToYear: function(){
		var subject = arguments[0];
		var offset = parseInt(subject.attr('default')) - subject.val();
		this.jumpTo(offset);
	}
};