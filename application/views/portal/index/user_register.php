<div class="subpage login_page">
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <h1>Registriraj se</h1>
                <form class="register_form">
                    <div class="form-group">
                        <div class="input_wrapper">
                            <input type="text" id="Firstname" required autocomplete="off" class="required" name="Firstname">
                            <label>Ime</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input_wrapper">
                            <input type="text" id="Lastname" required autocomplete="off" class="required" name="Lastname">
                            <label>Priimek</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input_wrapper">
                            <input type="text" id="Email" required autocomplete="off" class="required" name="Email">
                            <label>Email</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input_wrapper">
                            <input type="password" id="Password" required autocomplete="off" class="required" name="Password">
                            <label>Geslo</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input_wrapper">
                            <input type="password" id="RepeatPassword" required autocomplete="off" class="required" name="RepeatPassword">
                            <label>Ponovi geslo</label>
                        </div>
                    </div>
                    <button type="button" class="btn-primary btn-register" onclick="user_register();">
                        Registriraj se
                    </button>
                    <div class="error_box" id="ErrorRegister">
                    Za geslo je potrebno vsaj 6 znakov in ena velika črka.
                    </div>
                </form>
                <div class="text-center">
                    <label class="new-at">Že imaš račun?</label>
                    <a href="<?= BASE_URL . "/prijava"; ?>" title="Prijava" class="login-profile">
                        Vpiši se
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="<?php echo $Location; ?>" id="LoginUrl" />