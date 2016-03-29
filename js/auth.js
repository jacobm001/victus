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
		
		if(this.key !== null && this.key_expires !== null)
			return true;
		else
			return false;
	};

	this.validate_local_credentials = function() {
		if(this.key_expires < new Date())
			return true;
		else
			return false;
	};

	this.submit_credentials = function() {
		var user = $("#inputUsername").val();
		var pass = $("#inputPassword").val();
		
		console.log("submitting credentials:", user, pass);
		
		$.post('api/auth/login', {'user':user, 'pass':pass}, function(data) {
			console.log(data);
		});
	};
};