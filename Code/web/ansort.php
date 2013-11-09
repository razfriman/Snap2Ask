 //answer file with sort functions by time, rating, and pay
 <style>
    td.questionItem {
        margin-left: -1em;
        margin-top: 0.55em;
        width: 500px;
        height: 206px;
    }
    .stars
    {
        color: yellow;
    }
    .rating
    {
        margin-left: 1em;
    }
    </style>
<div id="mainContent">
        	<h1>MY ANSWERS</h1>
            <?php 
            class Answer
            {
                public $id;
                public $rating;
                public $desc;
                public $pay;
                public $datestr;
                public $answer;
            }
            $answers = array();
            for ($k = 0; $k < 20; $k++){
            $answers[$k] = new Answer();
            $answers[$k]->id = time() - rand(0,1000000);
            $answers[$k]->pay = rand(0,100);
            $answers[$k]->rating = rand(1,5);
            $answers[$k]->desc = 'hello';
            $answers[$k]->answer = 'bye';
            }
            function recent($a, $b)
            {
                return $b->id - $a->id;
            }
            function ratinghigh($a, $b)
            {
                return $b->rating - $a->rating;
            }
            function ratinglow($a, $b)
            {
                return -1 * ($b->rating - $a->rating);
            }
            function payhigh($a, $b)
            {
                return $b->pay - $a->pay;
            }
            function paylow($b, $a)
            {
                return $b->pay - $a->pay;
            }
            function select($lab)
            {
                if ($_POST['order'] === $lab)
                        echo 'selected'; 
            }
            ?>
            <form id='sort' action="<?=$PHP_SELF?>" method='post'>
            <label for="order">Sort By:</label>
            <select name="order">
            <option>Most Recent First</option>
            <option <?php select('Highest Pay First');?>>Highest Pay First</option>
            <option <?php select('Lowest Pay First');?>>Lowest Pay First</option>
            <option <?php select('Highest Rating First');?>>Highest Rating First</option>
            <option <?php select('Lowest Rating First');?>>Lowest Rating First</option>
            </select>
            <input type='submit' value='submit'/>
            </form>
            <table>
            <?php
            function stars($numStars)
            {
               echo '<span class="rating">Rating: </span>
               <span class="stars">';
               for ($count = 0; $count < $numStars; $count++)
                    echo '&#9733';
                echo '</span>';
            }
            function pay()
            {
               $numStars = rand(1,5);
               return $numStars * 20;		
            }
            $start1 = 80; //lower bound
            $inc1 = 0; // constant
            $start2 = 100;
            $inc2 = -1;
            $starIndex = 0;
            if ($_POST['order'] === 'Most Recent First')
            {
                usort($answers, "recent");
            }
            else if ($_POST['order'] === 'Highest Rating First')
            {
                usort($answers, "ratinghigh");
            }
            else if ($_POST['order'] === 'Lowest Rating First')
            {
                usort($answers, "ratinglow");
            }
            else if ($_POST['order'] === 'Highest Pay First')
            {
                usort($answers, "payhigh");
            }
            else if ($_POST['order'] === 'Lowest Pay First')
            {
                usort($answers, "paylow");
            }
            for($a = 0, $b = 80; $a < 20; $a+=$inc1, $a++, $b++)
            {
                $str = "https://snap2ask.s3.amazonaws.com/" . $b . ".jpeg";
            ?>
                <tr>
                <td>
                <div class="questionItem" style="display: inline-block; opacity: 1;">
                <img class="questionImage" src=<?php echo $str; ?> >
                <label>Science</label><label> Chemistry</label>
                <label><?php echo date("Y-m-d H:i:s",$answers[$a]->id); ?></label>
                </div>
                </td>
                <td class="questionItem">
                <div id="view-question-right">
    				
					<label>Your Pay: 
					<?php echo $answers[$a]->pay; echo stars($answers[$a]->rating); ?>
                    </label>
					<label>Description:</label>
					<p><?php echo $answers[$a]->desc; ?></p>
					
					<label>Answer:</label>
					<p><?php echo $answers[$a]->answer; ?></p>
				</div>
                </td>
                </tr>
                
            <?php
            }
            ?>
                
                </table>
			
		</div>
