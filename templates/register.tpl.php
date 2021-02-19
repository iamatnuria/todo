<?php

include 'header.tpl.php';

?>

    <main class="container">
        <div class="d-flex justify-content-center h-100">
            <div class="card">
                <div class="card-header">Sign up:</div>
                <div class="card-body">
                    <form class="form" method="POST" action="<?= BASE;?>user/reg">
                        <div class="input-group form-group">
                            <label for="uname">Username:
                                <input type="text" class="form-control" name="uname" placeholder="uname">
                        </div>
                        <div class="input-group form-group">
                            <label for="email">Email:
                                <input type="text" class="form-control" name="email" id="email" placeholder="email"></label>
                        </div>
                        <div class="input-group form-group">
                            <label for="passw">Password:
                                <input type="password"  class="form-control" id="passw" name="passw" placeholder="Password">
                            </label>
                        </div>
                        <div class="input-group form-group">
                            <label for="passw2">Repeat Password:
                                <input type="password"  class="form-control" id="passw2" name="passw2" placeholder="Password">
                            </label>
                        </div>

                        <div class="form-group">
                            <button  class="btn btn-primary" type="submit">Register</button>
                        </div>

                    </form>
                </div>

            </div>


    </main>

<?php
include 'footer.tpl.php';
?>