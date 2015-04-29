<script language="javascript">
	function checkEmail() {
		var email = document.getElementById('email');
		var filter = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
		if (!filter.test(email.value)) {
			alert('Adresa de mail introdusa nu este valida.');
			email.focus
			return false;
		}else{
			document.inscriere_newsletter.submit();
		}
	}
</script>
    <div class="inscrieTe">
	    <form name="inscriere_newsletter"  action="<?=tep_href_link(FILENAME_SUBSCRIERE_NEWSLETTER, '', 'NONSSL', false)?>" method="POST">
                <h3>Inscrie-te la Newsletter</h3>
                <p>Poti primi informatii utile
                    promotii, detalii despre
                    produsele noi etc.</p>
                <label for="companie">Companie: </label>
                <input type="text"  name="companie" id="companie">
                <label for="email">Email</label>
                <input id="email" name="email" type="text">
                <input type="text" name="name" id="hideme">
            <button class="default" onClick="checkEmail()">Subscrie</button>
        </form>
    </div>


