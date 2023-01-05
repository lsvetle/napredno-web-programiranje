<?php
   require APPROOT . '/views/includes/head.php';
?>

<div class="section-landing">
    <div class="navbar">
        <?php
        require APPROOT . '/views/includes/navigation.php';
        ?>
    </div>
    <link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/style.css">
    <div class="container-login">
        <div class="wrapper-login">
            <h2>Register</h2>

                <form
                    id="register-form"
                    method="POST"
                    action="<?php echo URLROOT; ?>/users/register"
                    >
                <input type="text" placeholder="Username *" name="username">
                <span class="invalidFeedback">
                    <?php echo $data['usernameError']; ?>
                </span>

                <input type="email" placeholder="Email *" name="email">
                <span class="invalidFeedback">
                    <?php echo $data['emailError']; ?>
                </span>

                <input type="password" placeholder="Password *" name="password">
                <span class="invalidFeedback">
                    <?php echo $data['passwordError']; ?>
                </span>

                <input type="password" placeholder="Confirm Password *" name="confirmPassword">
                <span class="invalidFeedback">
                    <?php echo $data['confirmPasswordError']; ?>
                </span>

                <button id="submit" type="submit" value="submit">Submit</button>

                <p class="options">Allready have account? <a href="<?php echo URLROOT; ?>/users/login">Log in!</a></p>
            </form>
        </div>
    </div>
</div>