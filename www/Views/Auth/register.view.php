<?php if(!empty($messageInfoSendMail)): ?>
    <?php echo "<div class='alert alert-info'>" . $messageInfoSendMail[0] . "</div></br>";?>
<?php endif;?>
<h2>S'inscrire</h2>
<?php $this->partial("form", $form, $formErrors) ?>
