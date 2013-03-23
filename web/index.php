<?php
$pageTitle = 'HappinessXchange';
require_once('header.php');
?>
      <div class="maincontents container">
        <div class="row">
            <div class="logo"></div>
        </div>
        <div class="row">
            <div class="span6 activities">
                <h4>Recent Activities</h4>
                <div class="row-fluid">
                    <div class="span2"><img src="images/nouser.jpg"/></div>
                    <div class="span8"><a href="#">Muthu</a> Gave <a href="#">xyzobject</a> to <a href="#">vincent</a> and recieved <a href="#">yyyobject</a></div>
                    <div class="span2"><img src="images/nouser.jpg"/></div>
                </div>
                <div class="row-fluid">
                    <div class="span2"><img src="images/nouser.jpg"/></div>
                    <div class="span8"><a href="#">Muthu</a> Gave <a href="#">xyzobject</a> to <a href="#">vincent</a> and recieved <a href="#">yyyobject</a></div>
                    <div class="span2"><img src="images/nouser.jpg"/></div>
                </div>
                <div class="row-fluid">                   
                    <div class="span2"><img src="images/nouser.jpg"/></div>
                    <div class="span8"><a href="#">Muthu</a> Gave <a href="#">xyzobject</a> to <a href="#">vincent</a> and recieved <a href="#">yyyobject</a></div>
                    <div class="span2"><img src="images/nouser.jpg"/></div>
                </div>
            </div>
            <div class="span4 signup">
                <h4>Help Spread the Happiness!</h4>
                <h5>It's pretty simple really, here's how it works:</h5>
                <div class="text-left howto">
                    <ol>
                        <li>Sign up with your Facebook account.</li>
                        <li>Pick a friend* who you want to send a gift to.</li>
                        <li>Wait for a friend to send you a gift too.</li>
                    </ol>
                    <p class="fineprint text-right">* p.s. your friend won't know it was you =)</p>
                </div>
                <div class="btn btn-large btn-primary fb-login-button" data-show-faces="false" data-width="200" data-max-rows="1" onclick="fb_login();"><i class=" icon-user icon-white"></i>&nbsp; Facebook Connect</div>
            </div>
        </div>
      </div>
<?php
require_once('footer.php');
?>