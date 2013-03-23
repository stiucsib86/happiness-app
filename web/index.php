<?php
$pageTitle = 'HappinessXchange';
require_once('header.php');
?>
      <div id="mainContent">

            <div id="leftContainer">
                <div class="logo"></div>
                <h4>Recent Activities</h4>
                <div id="activities">
                  <ul>
                    <li>
                        <div class="fromImg"><img src="images/1.jpg"/></div>
                        <div class="activityDesc"><a href="#">Muthu</a> gave a <a href="#">Canon EOS Rebel T3i 18MP CMOS...</a> to <a href="#">Vincent</a> and recieved a <a href="#">GoPro HERO3</a> from him</div>
                        <div class="toImg"><img src="images/2.jpg"/></div>
                        <div class="clearfix"></div>
                    </li>
                    <li>
                        <div class="fromImg"><img src="images/3.jpg"/></div>
                        <div class="activityDesc"><a href="#">Binghan</a> gave a <a href="#">Parrot AR Drone 2...</a> to <a href="#">Vincent</a> and recieved a <a href="#">Retro Tamagotchi Limited Edition</a> from him</div>
                        <div class="toImg"><img src="images/4.jpg"/></div>
                        <div class="clearfix"></div>
                    </li>
                    <li>                   
                        <div class="fromImg"><img src="images/5.jpg"/></div>
                        <div class="activityDesc"><a href="#">Abhinit</a> gave a <a href="#">Guitar Hero World Tour</a> to <a href="#">Binghan</a> and recieved a <a href="#">PlayStation 3 Black Version</a> from him</div>
                        <div class="toImg"><img src="images/6.jpg"/></div>
                        <div class="clearfix"></div>
                    </li>
                    <li>
                        <div class="fromImg"><img src="images/1.jpg"/></div>
                        <div class="activityDesc"><a href="#">Muhammad</a> gave a <a href="#">Carton of Milk</a> to <a href="#">Muthu</a> and recieved a <a href="#">Can of Soya Bean</a> from him</div>
                        <div class="toImg"><img src="images/2.jpg"/></div>
                        <div class="clearfix"></div>
                    </li>
                    <li>
                        <div class="fromImg"><img src="images/3.jpg"/></div>
                        <div class="activityDesc"><a href="#">Muhammad</a> gave a <a href="#">Halogen Light Stick</a> to <a href="#">Vincent</a> and recieved a <a href="#">Blue Lightsaber</a> from him</div>
                        <div class="toImg"><img src="images/4.jpg"/></div>
                        <div class="clearfix"></div>
                    </li>
                    <li>                   
                        <div class="fromImg"><img src="images/5.jpg"/></div>
                        <div class="activityDesc"><a href="#">Abhinit</a> gave a <a href="#">Super Mario Xtreme</a> to <a href="#">Muhammad</a> and recieved a <a href="#">Zelda Classic Adventure</a> from him</div>
                        <div class="toImg"><img src="images/6.jpg"/></div>
                        <div class="clearfix"></div>
                    </li>
                  </ul>
                </div>
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