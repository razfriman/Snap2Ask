 <!--answer file with sort functions by time, rating, and pay-->
 <style>
    td.questionItem {
        margin-left: -1em;
        margin-top: 0.55em;
        width: 500px;
        height: 206px;
        color:white
    }
    label.answer
    {
        position:relative;
        color: blue;
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
    <?php
     /*for ($x = 0; $x < 150; $x++){
      $qdata = getQuestionInfo($x);
      //var_dump($qdata);
      for ($y = 0, $numAns = count($qdata["answers"]); $y < $numAns; $y++){
            $ansdata = $qdata["answers"][$y];
            if ($ansdata['tutor_id'] === $_SESSION['user_id'])
            {*/
                /*
                echo '<br>' . 'hello' . $_SESSION['user_id'];
                echo '<br>' . $qdata['id'];
                echo '<br>' . $qdata['student_id'];
                echo '<br>' . $qdata['description'];
                echo '<br>' . $qdata["subcategory"];
                echo '<br>' . $qdata["category"];
                echo '<br>' . $qdata["image_url"];
                echo '<br>' . $qdata["date_created"];
                echo '<br>' . $ansdata['text'];
                echo '<br>' . $ansdata['tutor_id'];
                var_dump($ansdata['rating']);
                
            }
        }
     }*/
      //echo '<br>' . $qdata["answers"][0]['tutor_id'] === $_SESSION['user_id'];
     ?>
<div id="mainContent">
            <h1>MY ANSWERS</h1>
            <?php 
            /*
            class Answer
            {
                public $id;
                public $cat;
                public $rating;
                public $desc;
                public $pay;
                public $datestr;
                public $answer;
                public $imgurl;
            }
            $answers = array();
            for ($x = 0, $k = 0; $x < 0; $x++){
                $qdata = getQuestionInfo($x);
                //var_dump($qdata);
                for ($y = 0, $numAns = count($qdata["answers"]); $y < $numAns; $y++){
                    $ansdata = $qdata["answers"][$y];
                    if ($ansdata['tutor_id'] === $_SESSION['user_id'])
                    {
                        $answers[$k] = new Answer();
                        $answers[$k]->id = $qdata['id'];
                        $answers[$k]->pay = 50;
                        $answers[$k]->datestr = $qdata["date_created"];
                        $answers[$k]->cat = $qdata["category"];
                        $answers[$k]->rating = var_dump($ansdata['rating']);
                        $answers[$k]->desc = $qdata['description'];
                        $answers[$k]->answer = $ansdata['text'];
                        $answers[$k]->imgurl = $qdata["image_url"];
                        $k++;
                    }
                }
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
            }*/
            function select($lab)
            {
                if ($_POST['order'] === $lab)
                        echo 'selected'; 
            }
            ?>
           <!-- <form id='sort' action="<?=$PHP_SELF?>" method='post'>
            <label for="order">Sort By:</label>
            <select name="order">
            <option>Most Recent First</option>
            <option <?php select('Highest Pay First');?>>Highest Pay First</option>
            <option <?php select('Lowest Pay First');?>>Lowest Pay First</option>
            <option <?php select('Highest Rating First');?>>Highest Rating First</option>
            <option <?php select('Lowest Rating First');?>>Lowest Rating First</option>
            </select>
            <input type='submit' value='submit'/>
            </form>-->
            <table>
            <?php
            function stars($numStars)
            {
               echo '<span class="rating">Rating: </span>
               <span class="stars">';
               if (!$numStars){
                echo '&#9733';
                return;
               }
               for ($count = 0; $count < $numStars; $count++)
                    echo '&#9733';
                echo '</span>';
            }
            /*
            if ($_POST['order'] === 'Most Recent First')
            {
                //usort($answers, "recent");
            }
            else if ($_POST['order'] === 'Highest Rating First')
            {
                //usort($answers, "ratinghigh");
            }
            else if ($_POST['order'] === 'Lowest Rating First')
            {
               // usort($answers, "ratinglow");
            }
            else if ($_POST['order'] === 'Highest Pay First')
            {
               // usort($answers, "payhigh");
            }
            else if ($_POST['order'] === 'Lowest Pay First')
            {
               // usort($answers, "paylow");
            }*/
                $qdata = getAnswerInfo($_SESSION['user_id']);
                //var_dump($qdata);
                for ($y = 0, $numAns = count($qdata["answers"]); $y < $numAns; $y++){
                    $ansdata = $qdata["answers"][$y];
            ?>
                <tr>
                <td>
                <div class="questionItem" style="display: inline-block; opacity: 1;">
                <img class="questionImage" src=<?php echo $qdata['questions'][$y]["image_url"] ?> >
                <label><?php echo $qdata['questions'][$y]["category"] ?></label><label><?php echo $qdata['questions'][$y]["subcategory"] ?></label>
                <label><?php echo $qdata['questions'][$y]["date_created"]; ?></label>
                </div>
                </td>
                <td class="questionItem">
                <div id="view-question-right">
    				
					<label>Your Pay: 
					<?php echo '50'; stars(rand(1,5)); ?>
                    </label>
					<label class="answer">Question: </label>
					<label class="text"><?php echo $qdata['questions'][$y]['description'] ?></label>
					
					<label class="answer">Answer:</label>
					<label class="text"><?php echo $ansdata['text']; ?></label>
				</div>
                </td>
                </tr>
                
            <?php
            }
            ?>
                
                </table>
			
		</div>
