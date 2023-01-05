<?php
   require APPROOT . '/views/includes/headCalendar.php';
   ?>
<div class="navbar-dark">
  <?php
        require APPROOT . '/views/includes/navigation.php';
        ?>
</div>
<body>
    <div class="section-landing">
        <div class="container">
          <!-- Responsive calendar - START -->
          <div class="responsive-calendar">
            <div class="controls">
              <a class="pull-left" data-go="prev"
                ><div class="btn btn-primary">Prev</div></a
              >
                <h4><span data-head-year></span> <span data-head-month></span></h4>
              <a class="pull-right" data-go="next"
                ><div class="btn btn-primary">Next</div></a
              >
            </div>
            <hr />
            <div class="day-headers">
              <div class="day header">Mon</div>
              <div class="day header">Tue</div>
              <div class="day header">Wed</div>
              <div class="day header">Thu</div>
              <div class="day header">Fri</div>
              <div class="day header">Sat</div>
              <div class="day header">Sun</div>
            </div>
            <div class="days" data-group="days"></div>
        </div>
          <!-- Responsive calendar - END -->
        </div>
        <script src="<?php echo URLROOT ?>/public/js/jquery.js"></script>
        <script src="<?php echo URLROOT ?>/public/js/bootstrap.min.js"></script>
        <script src="<?php echo URLROOT ?>/public/js/responsive-calendar.js"></script>
        
        <script type="text/javascript">
          $(document).ready(function () {
            const date= new Date();
            const notifications =JSON.parse('<?php echo json_encode($data['notifications'])?>');
            const datesActiveArray = notifications.map(function(item){return item.date});
            let datesActiveObj = {};
            let number;
            datesActiveArray.forEach((date)=>{
              number =0;
              for (let i=0; i<datesActiveArray.length; i++) {
                if (datesActiveArray[i] == date)
                  number++;
              }
              datesActiveObj[date]={url:"<?php echo URLROOT;?>/notifications/calendar?date="+date, number: number};
            });

            $(".responsive-calendar").responsiveCalendar({
              time: `${date.getFullYear()}-${date.getMonth()+1}`,
              events: datesActiveObj
              //{"2022-06-10": { url:"<?php echo URLROOT;?>/notifications/create"}}
            });

            // const todayElement = document.getElementsByClassName
            // console.log(todayElement);
            // todayElement.classList.add('today');
            // todayElement.className += ' todayBorder';
            var root = document.getElementsByTagName( 'today' )[0]; // '0' to assign the first (and only `HTML` tag)
            root.setAttribute( 'class', 'todayBorder' );
          });
        </script>

      <nav class="notificaticationHead">
        <ul>
          <li>
              <a class="notificationList" href ="<?php echo URLROOT;?>/notifications/calendar">
                Notes
              </a> 
            </li>

          <li>
              <div>
                <?php 
                  $date = $_GET['date']?? ""
                ?>
                <?php if ($date != ""): ?>
                  <h3 class="noNotification">
                    "<?php echo $date; ?>"
                  </h3>
                  <?php endif; ?>
              </div> 
          </li>

          <li>
            <a class="nav-link icon" href ="<?php echo URLROOT;?>/notifications/calendar?sorted=DESC"> 
                <i class="fa fa-thin fa-arrow-up-short-wide"></i>
              </a>
          </li>

          <li>
            <a class="nav-link icon" href ="<?php echo URLROOT;?>/notifications/calendar?sorted=ASC"> 
                <i class="fa fa-thin fa-arrow-up-wide-short"></i>
            </a>
          </li>

          <li>
          <?php if(isLoggedIn()): ?>
            <a class="nav-link icon" href ="<?php echo URLROOT;?>/notifications/calendar?id=<?php echo $_SESSION['user_id']?>"> 
              <i class="fa fa-thin fa-user-lock"></i>
            </a>
          <?php endif; ?>
          </li>

          <li>
            <?php if(isLoggedIn()): ?>
              <a class ="icon" href ="<?php echo URLROOT;?>/notifications/create"> 
                <img class="addIcon" src="<?php echo URLROOT; ?>/public/img/addicon.png">
              </a>
              <?php endif; ?>
          </li>
        </ul>
      </nav>
      <div class ='container-notifications'>
        <div class ="noNotification">
                <?php if (empty($data['notifications'])): ?>
                  <h3 class="noNotification">
                    You don't have notification, start by log in and <a href ="<?php echo URLROOT;?>/notifications/create"> <span>
                    create some. </span></a>
                </h3>
                <?php endif; ?>
        </div>
        <?php foreach($data['notifications'] as $notification): ?>
          <div class="container-item">

              <!-- check if user is set and find the users notifications so he can modify only his notif -->
              <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] ==$notification->user_ID): ?>

                <a 
                  class="btnUpdate" 
                  href="<?php echo URLROOT . "/notifications/update/" . $notification->notification_ID ?>">
                   Update
                </a>

                <!-- we need form because delate is methode -->
                <form
                action ="<?php echo URLROOT . "/notifications/delete/" . $notification->notification_ID ?>" method ="POST"> 
                   <input type="submit" name ="delete" value="delete"
                   class = "btnDelete" onclick="return confirm('Are you sure you want to delete notification (<?php echo $notification->title ?>)?')">
                </form>
              <?php endif; ?>

              <h2>
                <?php echo $notification->title ?>
              </h2> 

              <h3 class = "warning">
                <?php if($notification->date < date("Y-m-d") && $notification->date !="0000-00-00" ): ?>
                -➤ Notification expired.
                <?php endif; ?>
              </h3>

              <h3>
                <?php echo '-➤ Created by: ' . $notification->username . '.'?>
              </h3>

              <h3>
                <?php echo '-➤ Created on ' . date('F j', strtotime($notification->created_at)) . ', ' . date('h:m', strtotime($notification->created_at)) . '.'?> 
              </h3>
              
              <h3>
                <?php if($notification->date !="0000-00-00" ): ?>
                  <?php echo '-➤ Deadline: ' . date('F j', strtotime
                ($notification->date)) . '.'?>
                <?php endif; ?>
              </h3>

              <p>
                <?php echo $notification->message?>
              </p>

            </div>
          <?php endforeach;  ?>
      </div>

      <div class="footer">
        <p>&copy; Copyright 2022 Luka Svetlečić</p>
    </div>
  </body>
</div>
</html>
