String.prototype.trim = function(){
	return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
};
var tangazo  = {
	ajaxObject: false,
	deleteElement: false,
	selectAllElement: false,
	selectOneElement: false,
	uploadElement: false,
	progressIndicator: false,
	progressIndicatorParent: false,
	settings: {
		source: 'ajax.php',
		uploader: 'iframe',
		maxPostSize: 0
	},
	init: function(){
		this.initElements();
		this.initMaxPostSize();
		this.initUploadObject();
		this.initUploadEvent();
		this.initSelectAllEvent();
		this.initDeleteEvent();
	},
	initSelectAllEvent: function(){
		var subject = this;
		subject.selectAllElement.click(function(){
			var checked = $(this).is(':checked');
			subject.selectOneElement.prop('checked', checked);
		});
	},
	initDeleteEvent: function(){
		var subject = this;
		subject.deleteElement.click(function(){
			var selectedBoxes = subject.getSelectedBoxes();
			if(!selectedBoxes) return;
			var records = [];
			selectedBoxes.each(function(){
				records.push($(this).val());
			});
			subject.doDeleteRecords(records.join(', '));
			return;
		});
	},
	doDeleteRecords: function(){
		var data = {
				"do": "delete",
				"records": arguments[0],
				"table": this.deleteElement.attr('name')
		};
		$.post(this.settings.source, data, function(){
			if(parseInt(arguments[0]) > 0) {
				location.reload();
			}
		});
	},
	getSelectedBoxes: function(){
		var selectedBoxes = this.selectOneElement.filter(':checked');
		if(!selectedBoxes.length) return false;
		var warning = selectedBoxes.length > 1 ? 'Are you sure you want to delete these ' + selectedBoxes.length + ' records?' : 'Are you sure you want to delete this record?';
		if(!confirm(warning)) return false;
		return selectedBoxes;
	},
	initElements: function(){
		this.deleteElement = $('#deleteRecords');
		this.selectAllElement = $('#selectall');
		this.selectOneElement = $('.selectone');
		this.uploadElement = $('#spendfile');
		this.progressIndicator = $('.progress');
		this.progressIndicatorParent = this.progressIndicator.parent();
	},
	initUploadObject: function(){
		var subject = this;
		subject.ajaxObject = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
		if(typeof this.ajaxObject.upload == 'undefined') return;
		subject.settings.uploader = 'ajax';
		subject.ajaxObject.upload.addEventListener('loadstart', subject.onLoadStart);
		subject.ajaxObject.upload.addEventListener('loadend', subject.onLoadEnd);
		subject.ajaxObject.upload.addEventListener('progress', subject.onProgress);
		subject.ajaxObject.onreadystatechange = function(){
			subject.onComplete(subject.ajaxObject);
		};		
	},
	initUploadEvent: function(){
		var subject = this;
		$('#spendfile').live('change', function(){
			if(!subject.uploadElement.val()) return;
			switch(subject.settings.uploader){
				case 'iframe':
					subject.iframeUpload(arguments[0]);
					break;
				case 'ajax':
					subject.ajaxUpload(arguments[0]);
					break;
			}
		});
		$('#uploadspendfile').mousedown(function(){
			$('#spendfile').change();
		});
	},
	initMaxPostSize: function(){
		var subject = this;
		$.post(this.settings.source, {"do": "postsize"}, function(){
			subject.settings.maxPostSize = parseInt(arguments[0]);
		});
	},
	iframeUpload: function(){
		alert('Kindly use either firefox, chrome, safari and not Internet Explorer.');
	},
	ajaxUpload: function(){
		var subject = this;
		var event = arguments[0];
		var data = new FormData();
		data.append('do', 'upload');
		var files = event.currentTarget.files;
		for(i in files){
			var file = files[i];
			if(!(file instanceof File)) continue;
			if(file.size > subject.settings.maxPostSize){
				subject.uploadElement.val('');
				subject.writeError('Please select a file with less than ' + (subject.settings.maxPostSize/1048576) + 'Mb.');
				return;
			}else{
				subject.clearError();
			}
			if(file.type.trim() != 'application/vnd.ms-excel'){
				subject.uploadElement.val('');
				subject.writeError('Please upload Excel files only.');
				return;
			}else{
				subject.clearError();
			}
			data.append(file.name, file);
		}
		subject.ajaxObject.open("POST", this.settings.source);
		subject.ajaxObject.setRequestHeader("Cache-Control", "no-cache");
		subject.ajaxObject.send(data);
		return true;
	},
	onLoadStart: function(){},
	onLoadEnd: function(){
		tangazo.progressIndicator.html('').width(0);
	},
	onProgress: function(){
		var subject = tangazo;
		var event = arguments[0];
		if(!event.lengthComputable) return;
		var parentWidth = subject.progressIndicatorParent.width();
		var width = Math.floor(((event.position / event.totalSize) * parentWidth)) - 2;
		var widthPercentage = Math.ceil((width/parentWidth) * 100);
		subject.progressIndicator.width(width).html((widthPercentage + '%'));
		if(widthPercentage == 100) $('.processing').fadeIn();
	},
	onComplete: function(){
		var subject = this;
		if(arguments[0].readyState != 4 || arguments[0].status != 200) return;
		$('.processing').css({display: 'none'});
		var response = jQuery.parseJSON(arguments[0].responseText);
		var html = [];
		for(i in response.inserts){
			var insert = response.inserts[i];
			html.push('<div class="row import">');
			html.push('<div class="column grid10of10">' + insert.source + '</div>');
			html.push('<div class="column grid3of10">Media Expenditure</div><div class="column grid2of10">' + insert.campaign + '</div>');
			html.push('<div class="column grid3of10">New Companies</div><div class="column grid2of10">' + insert.company + '</div>');
			html.push('<div class="column grid3of10">New Brands</div><div class="column grid2of10">' + insert.brand + '</div>');
			html.push('<div class="column grid3of10">New Sections</div><div class="column grid2of10">' + insert.section + '</div>');
			html.push('<div class="column grid3of10">New Sub Sections</div><div class="column grid2of10">' + insert.subSection + '</div>');
			html.push('<div class="column grid3of10">New Media</div><div class="column grid2of10">' + insert.media + '</div>');
			html.push('<div class="column grid3of10">Import Time</div><div class="column grid7of10">' + insert.creationTime + '</div>');
			html.push('</div>');
		}
		if(!html.length) {
			subject.writeError('Please select a valid MS Excel file.');
		}else{
			subject.clearError();
		}
		subject.uploadElement.val('');
		$('.imports').css({display: 'none'}).prepend(html.join("\n")).fadeIn();
	},
	clearError: function(){
		$('.errorBox').html('').fadeOut();
	},
	writeError: function(){
		this.writeBox($('.errorBox'), arguments[0]);
	},
	writeBox: function(){
		var subject = arguments[0];
		subject.css({display: 'none'});
		subject.html(arguments[1]);
		subject.fadeIn(function(){
			setTimeout(function(){
				subject.html('');
				subject.fadeOut();
			}, 15000);
		});		
	}
};