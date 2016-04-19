/*	To execute to login at facebook (on the login page)
	Insert into the facebook login page
	
	The module should past the email and the password to the script via options
	
	By Niradr : nyradr@protonmail.com
	
	TODO 	: None
	STATUS 	: tested but work only when cookies is avaible and not private windows
*/
var inp_mail = document.getElementById("email");
var inp_pass = document.getElementById("pass");

if (inp_mail != null){
	inp_mail.value = self.options.mail;
	inp_pass.value = self.options.pass;
	
	inp_mail.form.submit();
}
self.port.emit("end", "");
