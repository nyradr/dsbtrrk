# dsbtrrk
DSB project

#Dependences

sigma.js : http://sigmajs.org/<br>
Sigma files must be placed in serv/trrk/watch/sigma<br>
firefox module devellopement tools : https://developer.mozilla.org/en-US/Add-ons/SDK/Tools/jpm#Installation

#Scanner
Scanner start in fbscan.js<br>
To change the entry point change 'id' : also change mail and mdp to a valid facebook account<br>
<pre><code>
function scan(){
  var scan = new FbScan(2); // number of persons to scan
  scan.start("id", "mail", "mdp");
}
</pre></code>

Also replace by a mysql connexion in serv/trrk/send/fb/utils.php
<pre><code>
/// OPEN MYSQL CONNEXTION
</code></pre>
And connexions constants in serv/trrk/watch/utils.php

To compile the module run the command
<pre><code>
jpm xpi
</pre></code>
Once the module is compiled you need to open the generated file <code>@trrk-0.0.1.xpi</code> in firefox<br>
The scan start when you click on the firefox icon in the module toolbar.<br>
The only way to stop the scan after it launch is to close every tabs.<br>

#Data utilisation:

Sample of data utilisation is in serv/trrk/watch<br>
A possible entry point is statistics.js : <code>host/trrk/watch/statistics.js</code>
