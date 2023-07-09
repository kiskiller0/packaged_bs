const msgOverlay = document.querySelector('.msgOverlay');
const msgBox = document.querySelector('.msg');

const form = document.querySelector('form');

form.addEventListener('submit', e => {
    console.log(`submitting!`);
    e.preventDefault()
    sendLoginInfo();
});

function sendLoginInfo() {
    fetch('../api/login.php', {
        method: 'post',
        body: new FormData(form)
    })
        .then(raw => raw.json())
        .then(json => {
            if (json.error) {
                msgOverlay.classList.remove('hidden');
                msgOverlay.classList.add('error');
                msgBox.innerText = json.msg;
            } else {
                // msgOverlay.classList.add('hidden');
                msgOverlay.classList.remove('hidden');
                msgBox.innerText = 'Logged In Successfully!';
                msgOverlay.classList.remove('error');

                console.log(`now you are being redirected ...`);

                setTimeout(() => {
                    window.location = '../..';
                }, 1500)
            }
        })
        .catch(err => {
            console.log(err)
        })
}


