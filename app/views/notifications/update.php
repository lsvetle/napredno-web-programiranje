<?php
   require APPROOT . '/views/includes/headCalendar.php';
   ?>
<div class="navbar-dark">
  <?php
        require APPROOT . '/views/includes/navigation.php';
        ?>
</div>

<div class="container center">
    <h1>Update notification</h1>

    <!-- //looking for specific data -->
    <form action="<?php echo URLROOT;?>/notifications/update/<?php echo $data['notification']->notification_ID?>" method="POST">
        <div class="form-item">
            <input class="title" type="text" name="title" value="<?php echo $data['notification']->title?>">
            <span class="invalidFeedback">
                    <?php echo $data['titleError']; ?>
            </span>
        </div>

        <div class="form-item">
            <textarea name ="message"> <?php echo $data['notification']->message ?></textarea>
            <span class="invalidFeedback">
                    <?php echo $data['messageError']; ?>
            </span>
        </div>

        <div class="form-item">
            <h3>
                If you have a deadline:
            </h3>
            <input class="date" type="date" name="date"  value="<?php echo $data['notification']->date?>">
            <span class="invalidFeedback">
                    <?php echo $data['dateError']; ?>
            </span>
        </div>

        <button class="btnAdd" name="submit" type="submit">Submit</button>
    </form>

</div>