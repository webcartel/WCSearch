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
		query: null,
		results: null,
	},

	mounted: function() {

	},

	methods: {
		getResults() {
			var params = new URLSearchParams()
			params.append('query', this.query)
			axios.post(wcsearch_ajax.url + '?action=get_search_results', params)
			.then(function (response) {
				this.results = response.data
			}.bind(this))
			.catch(function (error) {
				console.log(error)
			})
		},
	},
})