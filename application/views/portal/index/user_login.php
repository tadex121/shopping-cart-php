<div class="subpage login_page">
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <h1>Vpiši se</h1>
                <div class="form-group">
                    <div class="input_wrapper">
                        <input type="text" id="EmailLogin" required autocomplete="off" class="required" value="">
                        <label>Email</label>
                    </div>
                </div>
                <div class="input_wrapper">
                    <input type="password" id="PasswordLogin" required autocomplete="off" class="required" value="">
                    <label>Geslo</label>
                </div>
                <div class="user-login-wrapper">
                    <button type="button" class="btn-primary mt-4" onclick="user_login('')">
                        Vpiši se
                    </button>
                    <div class="error_box" id="ErrorLogin">
                    </div>
                    
                    <div class="text-center">
                        <a href="<?= BASE_URL . "/registracija" ; ?>" title="Ustvari nov profil" class="create-new-profile">
                        Ustvari nov profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="<?php echo $Location; ?>" id="LoginUrl" />
