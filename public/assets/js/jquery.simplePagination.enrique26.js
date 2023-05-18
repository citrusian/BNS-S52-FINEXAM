/**
 * jquery.simplePagination.js
 * @version: v1.0.0
 * @author: Sebastian Marulanda http://marulanda.me
 * @see: https://github.com/smarulanda/jquery.simplePagination
 */

(function($) {

	$.fn.simplePagination = function(options) {
		
		var defaults = {
			perPage: 5,
			containerClass: '',
			previousButtonClass: '',
			nextButtonClass: '',
			previousButtonText: 'Previous',
			nextButtonText: 'Next',
			currentPage: 1,
			toEntriesText:'to',/*edit labels for entries*/
			entriesTitle:'entries',/*edit labels for entries*/
			paginatorContainer:'',/*add reference to container paginator*/
			paginatorAlign:'center'/* control alignment*/
		};

		var settings = $.extend({}, defaults, options);

		return this.each(function() {			
			var $rows = $('tbody tr', this);
			var pages = Math.ceil($rows.length/settings.perPage);

			var container = document.createElement('div');
			var bPrevious = document.createElement('button');
			var bNext = document.createElement('button');
			var of = document.createElement('span');

			bPrevious.innerHTML = settings.previousButtonText;
			bNext.innerHTML = settings.nextButtonText;

			container.className = settings.containerClass;
			bPrevious.className = settings.previousButtonClass;
			bNext.className = settings.nextButtonClass;

			bPrevious.style.marginRight = '8px';
			bNext.style.marginLeft = '8px';
			container.style.textAlign = settings.paginatorAlign;
			container.style.marginBottom = '20px';

			container.appendChild(bPrevious);
			container.appendChild(of);
			container.appendChild(bNext);

			if(settings.paginatorContainer==''){
				$(this).after(container);
			}else{
				// this prevents create many control paginator container every time the function is called
				$(settings.paginatorContainer).html('');
				$(settings.paginatorContainer).append(container);
			}
			
			update();

			$(bNext).click(function() {
				if (settings.currentPage + 1 > pages) {
					settings.currentPage = pages;
				} else {
					settings.currentPage++;
				}

				update();
			});

			$(bPrevious).click(function() {
				if (settings.currentPage - 1 < 1) {
					settings.currentPage = 1;
				} else {
					settings.currentPage--;
				}

				update();
			});

			function update() {
				var from = ((settings.currentPage - 1) * settings.perPage) + 1;
				var to = from + settings.perPage - 1;

				if (to > $rows.length) {
					to = $rows.length;
				}

				$rows.hide();
				$rows.slice((from-1), to).show();

				//reorder labels paginator
				of.innerHTML = from +' '+settings.toEntriesText +' '+ to +' - '+ settings.entriesTitle +' : '+ $rows.length;

				if ($rows.length <= settings.perPage) {
					$(container).hide();
				} else {
					$(container).show();
				}
			}
		});

	}

}(jQuery));
