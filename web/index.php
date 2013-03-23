<?php
$pageTitle = 'HappinessXchange';
require_once('header.php');
?>
      <div id="mainContent">

            <div id="activities">
                <div class="logo"></div>
                <h4 id="activityHeading">Recent Activities</h4>
                <ul>
                    <li>
                        <div class="fromImg"><img src="images/nouser.jpg"/></div>
                        <div class="activityDesc"><a href="#">Muthu</a> Gave <a href="#">xyzobject</a> to <a href="#">vincent</a> and recieved <a href="#">yyyobject</a></div>
                        <div class="toImg"><img src="images/nouser.jpg"/></div>
                        <div class="clearfix"></div>
                    </li>
                    <li>
                        <div class="fromImg"><img src="images/nouser.jpg"/></div>
                        <div class="activityDesc"><a href="#">Muthu</a> Gave <a href="#">xyzobject</a> to <a href="#">vincent</a> and recieved <a href="#">yyyobject</a></div>
                        <div class="toImg"><img src="images/nouser.jpg"/></div>
                        <div class="clearfix"></div>
                    </li>
                    <li>                   
                        <div class="fromImg"><img src="images/nouser.jpg"/></div>
                        <div class="activityDesc"><a href="#">Muthu</a> Gave <a href="#">xyzobject</a> to <a href="#">vincent</a> and recieved <a href="#">yyyobject</a></div>
                        <div class="toImg"><img src="images/nouser.jpg"/></div>
                        <div class="clearfix"></div>
                    </li>
                </ul>
            </div>

            <div id="signup">
                <h4 id="happiness">Help Spread the Happiness!</h4>
                <h5>It's pretty simple really, here's how it works:</h5>
                <div class="text-left howto">
                    <ol>
                        <li>Sign up with your Facebook account.</li>
                        <li>Pick a friend* who you want to send a gift to.</li>
                        <li>Wait for a friend to send you a gift too.</li>
                    </ol>
                    <p class="fineprint text-right">* p.s. your friend won't know it was you =)</p>
                </div>
                <div id="fbButton" class="btn btn-large btn-primary fb-login-button" data-show-faces="false" data-width="200" data-max-rows="1" onclick="fb_login();"><i class=" icon-user icon-white"></i>&nbsp; Facebook Connect</div>
            </div>
            <div class="clearfix"></div>
      </div>
<?php
require_once('footer.php');
?>