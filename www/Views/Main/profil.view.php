<h1>Profil user :</h1>
<pre>
<?php var_dump($informationsUser) ?>
</pre>
<?php $this->partial("form", $form, $formErrors) ?>

<?php if(!empty($messageInfoSendMail)): ?>
<?php print_r($messageInfoSendMail);?>
<?php endif;?>