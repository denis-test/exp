<div class="container">

    <?php if (!isset($errors)) {
        $errors = array();
    } 
    
    $getValidationClass = function ($field) use ($errors) {
        return isset($errors[$field])?'has-error has-feedback':'';
    };
    
    $getErrorBody = function ($field) use ($errors){
        if (isset($errors[$field])){
          return '<span class="glyphicon glyphicon-remove form-control-feedback"></span><span class="pull-right small form-error">'.$errors[$field].'</span>';
        }
            return '';
    }
    
    ?>
    
    
    <?php foreach ($msgs as $error) { ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
            <strong>Error!</strong> <?php echo $error ?>
        </div>
    <?php } ?>
    
   
    <form class="form-signin" role="form" method="post" action="<?php echo $getRoute('profile')?>">
        <h2 class="form-signin-heading">Profile</h2>
        <div class="form-group <?php echo $getValidationClass('name') ?>">
            <input type="name" class="form-control" placeholder="Name" value="<?php echo @$profile->name ?>" required autofocus name="name">
            <?php echo $getErrorBody('name')?>
        </div>
        <div class="form-group <?php echo $getValidationClass('second_name') ?>">
            <input type="name" class="form-control" placeholder="second_name" value="<?php echo @$profile->second_name ?>" required name="second_name">
            <?php echo $getErrorBody('second_name')?>
        </div>
        <div class="form-group <?php echo $getValidationClass('info') ?>">
            <input type="name" class="form-control" placeholder="info" value="<?php echo @$profile->info ?>" name="info">
            <?php echo $getErrorBody('info')?>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Save</button>
        <?php $generateToken()?>
    </form>

</div>
