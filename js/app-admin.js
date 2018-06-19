var wcsearchAdmin = new Vue({
	el: '#wcsearch-admin',

	data: {
		appIsLoad: false,
		activeTabName: 'indexation',
		postTypesList: [],
		itemsToIndex: null,
	},

	mounted: function() {
		this.getPostTypes()
		this.getAllItemsToIndex()
		this.appIsLoad = true
	},

	methods: {
		getPostTypes() {
			axios.get(ajaxurl, {params: {action: 'get_pt'}})
			.then(function (response) {
				for ( var postType in response.data ) {
					this.postTypesList.push({ type: response.data[postType], toindex: true })
				}
			}.bind(this))
			.catch(function (error) {
				console.log(error)
			});
		},

		getAllItemsToIndex() {
			axios.get(ajaxurl, {params: {action: 'get_all_items_to_index'}})
			.then(function (response) {
				this.itemsToIndex = response.data
			}.bind(this))
			.catch(function (error) {
				console.log(error)
			});
		},

		runIndexation() {
			var str = ''
			for (var i = 0; i < this.itemsToIndex.length; i++) {
				if ( (this.itemsToIndex.length - 1) == i ) {
					str += "'"+this.itemsToIndex[i]+"'"
				}
				else {
					str += "'"+this.itemsToIndex[i]+"', "
				}
			}
			var params = new URLSearchParams()
			params.append('itemstoindex', str)
			axios.post(ajaxurl + '?action=run_index', params)
			.then(function (response) {
				console.log(response)
			})
			.catch(function (error) {
				console.log(error)
			})
		},
	},

	computed: {

	}
})