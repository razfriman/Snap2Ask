<form action="processsearch.php" method="get">
    		<p>Logo Find your Subject&#x25BC;
<label class="hidelabel" for="bigsearch">Enter your search keyword:</label> <input autocomplete="on" autofocus="true" id="bigsearch" 
               placeholder="Search for your question here..." type="text" /> 
<select id="subjectdropdown"> <!--For iteration 2 use PHP to add to category menu--><option value="All Subjects">All Subjects</option> 
<optgroup label="<?php echo "Math"?>"><option value="All Math">All Math</option>
<option value="Calculus">Calculus</option> </optgroup>
<optgroup label="Math"><option value="<?php echo "Science"?>">All Science</option>
<option value="<?php echo "Calculus"?>">">Calculus</option> </optgroup> </select> 
<input class="sgnup" id="sgnUp" name="sgnBt" title="sgnup" type="submit" value="Search" />
</form>