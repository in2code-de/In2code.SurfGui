/****************************************************
 *                                                  *
 *  (c) 2013 in2code GmbH  www.in2code.de           *
 *                                                  *
 ****************************************************/

;
(function ($) {

	function createIframeDialog(targetUri) {
		var dialog = $('<div></div>');
		dialog.append('<iframe class="consoleFrame" src="' + targetUri + '"></iframe>');
		return dialog.dialog({
			width: '90%',
			resizable: false,
			height: 500
		});
	}

	var L10n = {
		labels: {
			en: {
				confirmDeployment: 'Really deploy Project: "%0"?'
			},
			de: {
				confirmDeployment: 'Projekt "%0" wirklich deployen?'
			}
		},
		language: 'en',
		detectLanguage: function () {
			var language = $('html').attr('lang');
			if (language) {
				this.language = language;
			}
		},
		getLabel: function (key) {
			return this.format(this.labels[this.language][key], this.getLabel.arguments[1]);
		},
		format: function (string) {
			var args = arguments;
			var pattern = new RegExp("%([0-" + (arguments.length - 1) + "])", "g");
			return String(string).replace(pattern, function (match, index) {
				return args[1][index];
			});
		}
	};

	L10n.detectLanguage();

	$('.In2code-SurfGui-Logfile-Link').on('click', function (e) {
		e.preventDefault();
		createIframeDialog($(this).attr('href'));
	});

	$('.In2code-SurfGui-Deploy-Button').each(function () {
		var $this = $(this);
		$this.click(function (e) {
			e.preventDefault();
			if (!confirm(
					L10n.getLabel('confirmDeployment', [$this.data('in2code-surfgui-deployment')])
				)) {
				return;
			}
			var form = $this.closest('form');
			var url = form.attr('action');
			var source = form.find('.In2code-SurfGui-Git-Source-Select').find('option:selected').val();
			url = url + '&source=' + source;
			createIframeDialog(url);
		});
	});

	$('.project__gitselector__refresh').on('click', function (e) {
		e.preventDefault();
		window.location.href = $(this).data('updateurl');
	});

}(jQuery));
