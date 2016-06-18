<form action="changepass.php" method="post">
    <fieldset>
        <div class="form-group">
         <b>Username :</b>  <input autocomplete="off" autofocus class="form-control" name="username" Value="<?php print_r($user_id); ?>" type="text" readonly/>
        </div>
        <div class="form-group">
            <input autocomplete="off" class="form-control" name="old password" placeholder="Old Password" type="password"/>
        </div>
        <div class="form-group">
            <input class="form-control" name="new password" placeholder="New Password" type="password"/>
        </div>
        <div class="form-group">
            <input class="form-control" name="confirmation" placeholder="Password (again)" type="password"/>
        </div>
        <div class="form-group">
            <button class="btn btn-default" type="submit">
                Change Password
            </button>
        </div>
    </fieldset>
</form>