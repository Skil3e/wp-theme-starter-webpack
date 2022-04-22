function createLabel( name: string, operator: string = "-" ) {
    const addSpace = name.split( operator ).join( " " );
    return addSpace.charAt( 0 ).toUpperCase() + addSpace.substring( 1 ).toLowerCase();
}


async function getToken( siteKey: string ) {
    const token = await grecaptcha.execute( siteKey, {
        action: 'submit'
    } )
    return token
}


export function sendMail( siteKey: string, url: string, requiredFields: string[] = [ "name", "email" ], onSuccess?: ( data ) => void ) {
    const form = document.getElementById( "contact-form" ) as HTMLFormElement;
    const submitBtn = document.querySelector( '#contact-form button[type="submit"]' )! as HTMLButtonElement;
    const errorContainer = document.getElementById( "server-response" )!;
    let isValid = false;
    form.onsubmit = ( e ) => {
        e.preventDefault();
        //Client side Validation
        submitBtn.disabled = true;
        errorContainer.innerText = "";
        let i = 0;
        for (i; i < requiredFields.length; i++) {
            const currentFieldName = requiredFields[i];
            const currentField = form[currentFieldName];
            console.log( currentFieldName )
            const errorField = document.getElementById( `error-${ currentFieldName }` )!;
            if (!currentField.value) {
                currentField.classList.add( "input__field--error" )
                errorField!.innerHTML = `${ createLabel( currentFieldName ) } is required`;
                isValid = false;
                i = 0;
                break
            } else {
                errorField.innerHTML = "";
                currentField.classList.remove( "input__field--error" );
                isValid = true;
            }
        }

        if (!isValid) {
            submitBtn.disabled = false;
            return
        }
        grecaptcha.ready( async () => {
            const token = await getToken( siteKey );
            let formData = new FormData( form ) as URLSearchParams;
            formData.append( 'reCaptcha', token );
            const body = new URLSearchParams( formData )
            const params = { method: 'POST', body: body };
            fetch( url, params )
                .then( res => res.json() )
                .then( data => {
                    if (data.code != 200) {
                        console.log( data.message )
                        submitBtn.disabled = false;
                        throw new Error( data.message );
                    }
                    submitBtn.disabled = false;
                    form.reset();
                    errorContainer.innerHTML = `<p class="form__success">${ data.message }</p>`
                    onSuccess && onSuccess( data )
                } )
                .catch( e => {
                    submitBtn.disabled = false;
                    errorContainer!.innerHTML = `<p class="input__error">${ e }</p>`
                } )
        } )
    }
}

