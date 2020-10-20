<?php
/**
 * @var string $error - Form error
 */
?>
<div class="row justify-content-center mt-5">
    <div class="col-md-6 border border-primary pt-3">
        <?if($error){?>
            <div class="alert alert-danger" role="alert">
                <?=$error?>
            </div>
        <?}?>
        <form name="login_form" method="post" action="">
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Login</label>
                <div class="col-sm-10">
                    <input name="login" type="text" class="form-control" id="inputEmail3">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <input name="password" type="password" class="form-control" id="inputPassword3">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">Sign in</button>
                </div>
            </div>
        </form>
    </div>
</div>