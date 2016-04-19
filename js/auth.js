function auth() {
	this.key;
	this.key_expires;
	this.is_authenticated = false;

	this.init = function() {
		if(this.check_for_credentials() && this.validate_local_credentials()) {
			this.is_authenticated = true;
		};
	};

	this.check_for_credentials = function() {
		this.key         = localStorage.getItem('key');
		this.key_expires = localStorage.getItem('key_expires');
		
		if(this.key != null && this.key_expires != null)
			return true;
		
		console.log("storage not found");
		return false;
	};

	this.validate_local_credentials = function() {
		if(new Date() < new Date(this.key_expires))
			return true;
		
		console.log("credentials suck");
		return false;
	};

	this.update_is_auth = function(val)
	{
		this.is_authenticated = val;
	}

	this.submit_credentials = function(v) {
		var user = $("#inputUsername").val();
		var pass = $("#inputPassword").val();
		var self = this;
		
		$.post('api/auth/login', {'user':user, 'pass':pass}, function(data) {
			data = JSON.parse(data);
			if(data.status == 'success') {
				localStorage.setItem('key', data.session_key);
				localStorage.setItem('key_expires', data.session_exp);
				self.key         = data.session_key;
				self.key_expires = data.session_exp;
				self.update_is_auth(true);
				
				v.get_recipes();
				v.set_active_view("recipe_index");
			}
		});
	};
};