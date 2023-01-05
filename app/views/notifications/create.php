<?php
   require APPROOT . '/views/includes/headCalendar.php';
   ?>
<div class="navbar-dark">
  <?php
        require APPROOT . '/views/includes/navigation.php';
        ?>
</div>

<div class="container center">
    <h1>Add new notification</h1>

    <form action="<?php echo URLROOT?>/notifications/create" method="POST">
        <div class="form-item">
            <input class="title" type="text" name="title" placeholder="Title">
            <span class="invalidFeedback">
                    <?php echo $data['titleError']; ?>
            </span>
        </div>

        <div class="form-item">
            <textarea name ="message" placeholder="Enter your message"></textarea>
            <span class="invalidFeedback">
                    <?php echo $data['messageError']; ?>
            </span>
        </div>

        <div class="form-item">
            <h3>
                If you have a deadline:
            </h3>
            <input class="date" type="date" name="date">
            <span class="invalidFeedback">
                    <?php echo $data['dateError']; ?>
            </span>
        </div>

        <button class="btnAdd" name="submit" type="submit">Submit</button>
    </form>

</div>