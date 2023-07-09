const btn = document.querySelector('input[type="submit"]');
const finput = document.querySelector('input[type="file"]');
const profileImg = document.querySelector("form img");
const form = document.querySelector("form");
const msgBox = document.querySelector("#msgBox");

form.addEventListener("submit", (e) => {
    e.preventDefault();
    let fd = new FormData(form);
    fetch("../api/signup.php", {
        method: "POST",
        body: fd,
    })
        .then((data) => data.json())
        .then((jsoned) => {
            msgBox.textContent = jsoned.msg;
            if (jsoned.error) {
                msgBox.classList.add("visible");
                msgBox.classList.remove("correct");
            } else {
                // #TODO: remove the visible class!
                // and when you are at it, remove or change the textContent.
                msgBox.classList.add("visible");
                msgBox.classList.add("correct");

                // alert(jsoned.username);
                console.log(`now you are being redirected ...`);

                setTimeout(() => {
                    window.location = '../views/login.php';
                }, 1500)
            }

            console.log(jsoned);
        })
        .catch((err) => {
            console.log(err);
        });
});

// preview the profile picture:

finput.addEventListener("change", (e) => {
    let imgFile = finput.files[0];
    let form = new FormData();
    form.append("picture", imgFile, imgFile["name"]);

    fetch("../api/tmpimage.php", {
        method: "POST",
        body: form,
    })
        .then((data) => {
            return data.json();
        })
        .then((jsoned) => {
            console.log(jsoned);
            if (jsoned["tmpImg"]) {
                profileImg.src = jsoned["tmpImg"];
                // msgBox.classList.to; // I don't even wanna know what I meant to do with this line!
            }
        })
        .catch((err) => {
            console.log(`error: ${err}`);
        });
    console.log(`changed!`);
});

/* #TODO
[*]-add finput.onchange(fetch => send current image to /tmpimage.php)
*/
