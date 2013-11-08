    <style>
    td.questionItem {
        margin-left: -1em;
        margin-top: 0.55em;
        width: 500px;
        height: 206px;
    }

    .rating
    {
        margin-left: 1em;
    }
    </style>
<div id="mainContent">
    		<h1>YOUR ANSWERS</h1>
            <?php 
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
            if ($_POST['order'] === 'Most Recent First')
            {
                $start1 = 80; //lower bound
                $inc1 = 0; // constant
                $start2 = 100;
                $inc2 = -1;
                $case = 1;
            }
            else
            {
                $start2 = 100; //upper bound
                $inc2 = 0; // constant
                $start1 = 80;
                $inc1 = 1;
                $case = 2;
            } 
            for($a = $start1, $b = $start2; $a < $b; $a+=$inc1, $b+=$inc2)
            {
                if ($case === 1)
                    $y = $b;
                else
                    $y = $a;
                $str = "https://snap2ask.s3.amazonaws.com/" . $y . ".jpeg";
            ?>
                <tr>
                <td>
                <div class="questionItem" style="display: inline-block; opacity: 1;">
                <img class="questionImage" src=<?php echo $str; ?> >
                <label>Science</label><label> Chemistry</label>
                <label>Date: 2013-11-07 04:07:20</label>
                </div>
                </td>
                <td class="questionItem">
                <div id="view-question-right">
    				
					<label>Your Pay: 
					<?php echo $y; ?><span class="rating">Rating: &#9733;&#9734;&#9734;&#9734;&#9734;</span></label></p>
					
					<label>Description:</label>
					<p><?php echo $question_info['date_created']; ?></p>
					
					<label>Answer:</label>
					<p><?php echo $question_info['description']; ?></p>
				</div>
                </td>
                </tr>
                
            <?php
            }
            ?>
                
                </table>
			
		</div>
