              <h4>Friends</h4>
              <div class="my-friends">
				<script>
                    var friends = JSON.parse(window.localStorage.getItem("friends"));
                    for(var i=0; i<friends.length; i++) {
                        //console.log('name', friends[i]['name'], 'id', friends[i]['id']);
                        document.write('<div class="row-fluid">');
						document.write('    <div class="span2"><img src="https://graph.facebook.com/'+friends[i]['id']+'/picture?type=square"/></div>');
						document.write('    <div class="span8"><a href="https://www.facebook.com/'+friends[i]['id']+'" target="_blank">'+friends[i]['name']+'</a></div>');
						document.write('<div class="span2"><a href="/dashboard/give-gift/?id='+friends[i]['id']+'" target="_self"><img class="giftbox" src="/images/favicon.png" border="0" /></a></div>');
						document.write('</div>');
                    }
                </script>
              </div>