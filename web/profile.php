                <h4>My Profile</h4>
                <div class="row-fluid"> 
                    <div class="span7">
                        <form name="profile" target="_self" method="post" action="/dashboard">
                            <div class="control-group">
                              <label class="control-label" for="profile-name">Name</label>
                              <div class="controls">
                                <input type="text" id="profile-name" placeholder="John Tan" value="">
                              </div>
                            </div>
                            <div class="control-group">
                              <label class="control-label" for="profile-email">Email</label>
                              <div class="controls">
                                <input type="text" id="profile-email" placeholder="john.tan@gmail.com" value="">
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
                                <input type="text" name="interest" id="profile-interests" value="">
                              </div>
                            </div>
                            <div class="control-group">
                              <label class="control-label" for="profile-gender">Gender</label>
                              <div class="controls">
                                <input type="text" name="gender" id="profile-gender" value="" readonly="readonly">
                              </div>
                            </div>
                            <div class="control-group">
                              <label class="control-label" for="profile-dob">Date of Birth</label>
                              <div class="controls">
                                <input type="text" name="dob" id="profile-dob" value="" readonly="readonly">
                              </div>
                            </div>
                            <div class="control-group">
                              <label class="control-label" for="profile-about">About Me</label>
                              <div class="controls">
                                <textarea id="profile-about" name="about_me" cols="30" rows="5"></textarea>
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