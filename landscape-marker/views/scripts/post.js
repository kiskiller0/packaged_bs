const commentForm = document.querySelector('form')

commentForm.addEventListener('submit', e => {
    e.preventDefault();

    const formData = new FormData(commentForm);
    formData.append('postid', new URLSearchParams(window.location.search).get('id'));

    fetch('../../api/add_comment.php', {
        method: "post",
        body: formData
    })
        .then(raw => raw.json())
        .then(jsoned => {
            console.log(jsoned);
            location.reload();
        });
})


// fetch userdata:

let ids = document.querySelectorAll('.by');
let imgs = document.querySelectorAll('.comment img');
let usernames = document.querySelectorAll('.comment .username');

for (let i = 0; i < ids.length; i++) {
    let currentId = ids[i];
    console.log(currentId.innerHTML);

    // fetch current id data:
    let f = new FormData();
    f.append('id', currentId.innerHTML);

    fetch("../../api/get_user_data_by_id.php", {
        method: 'post',
        body: f
    })
        .then(raw => raw.json())
        .then(json => {
            console.log(json)

            // here goes the rendering:
            console.log(json);
            usernames[i].innerHTML = json['msg']['username'];
            imgs[i].src = json['msg']['picture'] ? `../../public/profiles/${json['msg']['username']}` : `../../public/profiles/default/user.png`;
        })
        .catch(err => {
            console.log(`error: ${err}`);
        })
}
// let id = document.querySelectorAll('.by').innerHTML;
