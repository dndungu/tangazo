"use strict";
var panel = {
	init: function(){
		var module = this;
		$('select.jumpto', document).change(function(){
			var subject = $(this);
			switch(filter.filter){
				case 'weekly':
					module.doJumpToWeek();
					break;
				case 'monthly':
					module.doJumpToMonth();
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
		var yearSelector = $('select[name="year"]', document);
		var defaultYear = parseInt(yearSelector.attr('default'));
		var selectedYear = parseInt(yearSelector.val());
		var weekSelector = $('select[name="week"]', document);
		var defaultWeek = parseInt(weekSelector.attr('default'));
		var selectedWeek = parseInt(weekSelector.val());
		var offset = (selectedYear === defaultYear) ? (selectedWeek - defaultWeek) : -((defaultWeek - 1) + ((defaultYear - selectedYear - 1) * 12) + (53 - selectedWeek));
		this.jumpTo(offset);
	},
	doJumpToMonth: function(){
		var monthSelector = $('select[name="month"]', document);
		var defaultMonth = parseInt(monthSelector.attr('default'));
		var selectedMonth = parseInt(monthSelector.val());
		var yearSelector = $('select[name="year"]', document);
		var defaultYear = parseInt(yearSelector.attr('default'));
		var selectedYear = parseInt(yearSelector.val());
		var offset = (selectedYear === defaultYear) ? (selectedMonth - defaultMonth) : -((defaultMonth - 1) + ((defaultYear - selectedYear - 1) * 12) + (13 - selectedMonth));
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