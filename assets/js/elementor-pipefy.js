const form = {
    postid: document.getElementById('postid'),
    submit: document.getElementById('efp-btn-submit'),
    messages: document.getElementById('efp-messages'),
}

form.submit.addEventListener('click', (event) => {
    event.preventDefault();

    const request = new XMLHttpRequest();

    request.onload = () => {
        let responseObject = null;

        try {
            responseObject = JSON.parse(request.responseText);
        } catch (e) {
            console.error('Could not parse JSON');
        }

        if (responseObject) {
            handleResponse(responseObject);
        }
    };

    const requestData = `action=${efpsettings.action}&security=${efpsettings.security}&postid=${form.postid.value}`;

    request.open('POST', efpsettings.ajax_url); // O object_name é uma variável importada pela função wp_localize_script como array dentro de functions.php
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.send(requestData);

    const handleResponse = (responseObject) => {
        while (form.messages.firstChild) {
            form.messages.removeChild(form.messages.firstChild);
        }

        const responseMessages = () => {
            responseObject.messages.forEach((message) => {
                const element = document.createElement('span');
                element.textContent = message;
                form.messages.appendChild(element);
            });
            
            form.messages.style.display = 'block';
        }
        
        if (responseObject.ok) {
            responseMessages();
            form.messages.classList.add('efp-messages--success');
            form.messages.classList.remove('efp-messages--error');

        } else {
            responseMessages();
            form.messages.classList.add('efp-messages--error');
            form.messages.classList.remove('efp-messages--success');
        }
    }
});