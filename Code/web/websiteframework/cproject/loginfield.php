
<?php
//include("helloname.php");
if($name)
    echo "Hi" . $name;
else
{
?>
<form action="processlogin.php" method='post'>
<input autocomplete="on" autofocus="true" class="input" id="email" placeholder="E-mail
Address" type="text" /><label class="hidelabel" for="pass">Password </label>
<input id="pass" maxlength="64" name="pass" placeholder="Password" type="password" value="" />
<input class="sgnin" id="sgnin" name="sgnin" title="Sign in" type="submit" value="Sign in" />
<input class="sgnup" id="sgnUp" name="sgnUp" title="sgnup" type="submit" value="Sign Up" />
    					<div><input checked="checked" id="remember_me" name="keepMeSignInOption" type="checkbox" value="1" /><label for="remember_me">Remember&nbsp;me</label>&nbsp;
                        <a href="forgotpass.php">Forgot your password&nbsp;</a></div>
                        </form>
                        <div id="fb-root"></div>
<?php
}
?>