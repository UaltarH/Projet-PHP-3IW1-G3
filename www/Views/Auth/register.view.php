<h2>S'inscrire</h2>

<?php $this->partial("form", $form, $formErrors) ?>

<p>Information sur l'inscription:</p>
<?php if(!empty($messageInfoSendMail)): ?>
<?php print_r($messageInfoSendMail);?>
<?php endif;?>