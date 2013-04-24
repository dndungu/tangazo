"use strict";
var panel = {
	init: function(){
		var module = this;
		$('select.jumpto', document).change(function(){
			var subject = $(this);
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
		var subject = arguments[0];
		var defaultMonth = parseInt(subject.attr('default'));
		var selectedMonth = parseInt(subject.val());
		var yearSelector = $('select[name="year"]', document);
		var defaultYear = parseInt(yearSelector.attr('default'));
		var currentYear = parseInt(yearSelector.val());
		var offset = (currentYear === defaultYear) ? (selectedMonth - defaultMonth) : -((defaultMonth - 1) + ((defaultYear - selectedYear - 1) * 12) + (13 - selectedMonth));
		console.info(offset);
		this.jumpTo(offset);
	},
	doJumpToYear: function(){
		var subject = arguments[0];
		var defaultYear = parseInt(subject.attr('default'));
		var selectedYear = parseInt(subject.val());
		var offset = selectedYear - defaultYear;
		this.jumpTo(offset);
	}
};