	<script>
		jQuery.getJSON("http://happiness-app.ap01.aws.af.cm/user/?callback=?", function(data) {
			console.log(data);
			document.getElementById('profile-name').value = data.display_name;
			document.getElementById('profile-email').value = data.email;
			document.getElementById('profile-gender').value = data.gender;
			document.getElementById('profile-dob').value = data.dob;
			document.getElementById('profile-interests').value = data.interest;
			document.getElementById('profile-interests').value = data.interest_text;
			console.log(data.interest);
			document.getElementById('profile-about').value = data.about_me;
			if (data.username) {
				document.getElementById("profileImg").src = 'https://graph.facebook.com/' + data.username + '/picture?type=large';
				document.getElementById('fbProfileLink').innerHTML = '<a href="http://facebook.com/' + data.username + '/" target="_blank">View Facebook Profile</a>';
			}
		});
	</script>

                <h4>My Profile</h4>
                <div class="row-fluid"> 
                    <div class="span7">
                        <form name="profile" target="_self" method="post" action="/dashboard">
                            <div class="control-group">
                              <label class="control-label" for="profile-name">Name</label>
                              <div class="controls">
                                <input type="text" id="profile-name" class="span8" placeholder="John Tan" value="">
                              </div>
                            </div>
                            <div class="control-group">
                              <label class="control-label" for="profile-email">Email</label>
                              <div class="controls">
                                <input type="text" id="profile-email" class="span8" placeholder="john.tan@gmail.com" value="">
                              </div>
                            </div>
                            <div class="control-group">
                              <div class="controls">
                                <span id="fbProfileLink"></span>
                              </div>
                            </div>
                            <div class="control-group">
                              <label class="control-label" for="profile-gender">Interests</label>
                              <div class="controls">
                                <input type="text" name="interest" class="span8" id="profile-interests" value="">
                              </div>
                            </div>
                            <div class="control-group">
                              <label class="control-label" for="profile-gender">Gender</label>
                              <div class="controls">
                                <input type="text" name="gender" class="span8" id="profile-gender" value="" readonly="readonly">
                              </div>
                            </div>
                            <div class="control-group">
                              <label class="control-label" for="profile-dob">Date of Birth</label>
                              <div class="controls">
                                <input type="text" name="dob" class="span8" id="profile-dob" value="" readonly="readonly">
                              </div>
                            </div>
                            <div class="control-group">
                              <label class="control-label" for="profile-about">About Me</label>
                              <div class="controls">
                                <textarea id="profile-about" class="span8" name="about_me" cols="30" rows="5"></textarea>
                              </div>
                            </div>
                            <div class="control-group">
                              <div class="controls">
                                <input type="submit" class="btn btn-large btn-success" value="Save">
                              </div>
                            </div>
                          </form>
                    </div>
                    <div class="span5">
                        <img id="profileImg" src="images/nouser.jpg"/>
                    </div>
                </div>
                <div class="row-fluid"> 
                    <div class="fb-like" data-href="http://testbed1.seabedfactory.com/" data-send="true" data-width="450" data-show-faces="true"></div>  
                 </div>