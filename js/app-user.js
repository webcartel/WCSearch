document.addEventListener("DOMContentLoaded", function() {
	document.querySelector('#wcsearch-btn').addEventListener('click', function() {
		document.querySelector('html').setAttribute('style', 'overflow: hidden !important;');
		document.querySelector('#wcsearch-user').setAttribute('class', 'active');
	});
	document.querySelector('#search-window-cloce').addEventListener('click', function() {
		document.querySelector('#wcsearch-user').removeAttribute('class');
		document.querySelector('html').removeAttribute('style');
	});
});

var wcsearchUser = new Vue({
	el: '#wcsearch-user',

	data: {

	},

	mounted: function() {

	},

	methods: {
		
	},
})