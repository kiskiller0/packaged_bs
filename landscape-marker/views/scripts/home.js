let globalBlur = document.querySelector(".popup_background");

function makePopup(activateButton, popupWindow, closeButton) {
    if (activateButton == null || popupWindow == null || closeButton == null) {
        return
    }

    // switch (null) {
    //     case activateButton:
    //     case popupWindow:
    //     case closeButton:
    //         return;
    // }

    closeButton.addEventListener("click", (e) => {
        popupWindow.classList.add("hidden");
        globalBlur.classList.add("hidden");
    });

    activateButton.addEventListener("click", (e) => {
        popupWindow.classList.remove("hidden");
        globalBlur.classList.remove("hidden");
    });
}

makePopup(document.querySelectorAll("#footer .clickable")[0], document.querySelector("#add_post"), document.querySelector("#add_post .controls .clickable"))
makePopup(document.querySelectorAll("#footer .clickable")[1], document.querySelector("#add_place"), document.querySelector("#add_place .controls .clickable"))
makePopup(document.querySelectorAll("#footer .clickable")[2], document.querySelector("#add_event"), document.querySelector("#add_event .controls .clickable"))
makePopup(document.querySelector("#functions .parameters"), document.querySelector("#parameters.popup"), document.querySelector("#parameters .controls .clickable"))

const container = document.querySelector("#content");
// const content = document.querySelector(".realContent");
// refactoring the post fetching logic into one class:

class PostSet {

    // TODO: []- make the logic go idle if the div is "hidden" #urgent
    // or else, scrolling down the posts div would trigger the places to fetch new places
    // add an argument (trigger
    content = [];
    div = null; // where posts are gonna be injected/rendered as html elements
    lastPostIndex = 0; // no need for a function closure now!
    fetchPending = false; // this is to avoid race condition (calling multiple fetchs=> fetch same posts again!
    constructor(div, api) {
        if (div == null) {
            return;
        }
        this.api = api;
        this.div = div;
        // if no local posts, fetch!
        // this.getLocalPosts() || this.fetchNext();
        // this.getLocalPosts(); this.fetchNext();
        // first, get last n posts to begin with:
        console.log(`rendering ${this.api} for the first time!`);
        this.fetchNext();
        // extract previously saved posts from local storage:
    }

    getLocalPosts() {
        // window.localStorage.get('posts'); // stringify / un-stringify logic!
        return false;
    }

    fetchNext(url = this.api) {
        if (this.div.classList.contains("hidden") && this.lastPostIndex > 0) {
            console.log(`the tab that fetches from ${this.api} is hidden!`);
            return;
        }

        if (this.fetchPending) {
            console.log(`sorry, fetch is pending!`);
            return;
        }

        this.fetchPending = true;
        // this fetches n posts at maximum, n is defined server-side
        let form = new FormData();
        if (this.content.length > 0) {
            form.append("id", this.content.slice(-1)[0].id);
            console.log(`last element:`);
            console.log(this.content.slice(-1)[0].id || "no id!");
        }
        fetch(url, {
            method: "post",
            body: form,
            header: {},
        })
            // .then((raw) => raw.text())
            .then((posts) => {
                // console.log(posts);
                return posts.json();
            })
            .then((decoded) => {
                if (decoded.error) {
                    console.log(`error! ${decoded.msg}`);
                    return;
                }
                console.log(url);
                console.log(decoded);
                this.content = [
                    ...this.content,
                    // ...decoded.posts.filter((serialized) => JSON.parse(serialized[0])),
                    ...decoded.posts,
                ];
                // calling render:
                this.fetchPending = false;
                this.render();
            })
            .catch((err) => {
                console.log(`unhandled error in the fetchPosts api!`);
                console.log(err);
                this.fetchPending = false;
            });
    }

    render() {
        // this is supposed to render this.posts to this.div
        // for testing reasons, it is just going to print from last post!
        console.log("rendering ...");
        if (this.content.length === this.lastPostIndex) {
            return;
        }

        console.log(this.content.slice(this.lastPostIndex, this.content.length));

        // actual rendering to page:
        console.log("rendering ...");

        // TODO: replace this outdated way of rendering with a newer, template-based method
        for (let post of this.content.slice(
            this.lastPostIndex,
            this.content.length
        )) {

            console.log(`post link: ../../views/post.php?id=${post['id']}`);

            let a = document.createElement('a');
            a.href = `../../views/post.php?id=${post['id']}`;

            let userDiv = document.createElement('div')
            userDiv.classList.add('userDiv')

            let username = document.createElement('p')
            username.classList.add('username')

            let userImg = document.createElement('img')
            userImg.classList.add('userImg')

            username.innerHTML = post.user.username
            // userImg.src = `/public/profiles/` + (post.user.picture ? post.user.username : 'user.png')
            userImg.src = `/public/profiles/` + (post.user.picture ? post.user.username : 'default/user.png')

            userDiv.appendChild(userImg)
            userDiv.appendChild(username)

            let postContent = document.createElement('p')
            postContent.innerHTML = post.content || "content undefined!";
            postContent.classList.add('postContent')

            let postDate = document.createElement('p')
            postDate.innerHTML = post.date || "date undefined!";
            postDate.classList.add('postDate')

            let img = document.createElement('img')
            img.src = `public/posts/${post.id}`

            let postContainer = document.createElement('div');
            postContainer.classList.add('post')


            postContainer.appendChild(userDiv)
            postContainer.appendChild(postContent)
            postContainer.appendChild(img)
            postContainer.appendChild(postDate)
            postContainer.appendChild(document.createElement('hr'))

            // this.div.appendChild(postContainer)
            // TODO: I'm going to alter this rendering method
            a.appendChild(postContainer);
            this.div.appendChild(a);
        }

        this.lastPostIndex = this.content.length;
    }
}

const myPosts = new PostSet(document.getElementsByClassName("realContent")[0], "api/get_posts.php");


// adding the user img:

fetch('api/whoami.php', {
    method: 'post'
})
    .then(raw => raw.json())
    .then(jsoned => {
        document.querySelector('.userImg').src = jsoned.user.picture ? `public/profiles/${jsoned.user.username}` : `public/profiles/default/user.png`;
        document.querySelector("#topbar > a").href = `views/user.php?id=${jsoned.user.id}`;
    })
    .catch(err => {
        console.log(`error fetching profile picture: ${err}`)
    })


// add the serch functionality:

function addSearchFunctionality(textField, searchButton) {
    searchButton.addEventListener('click', (e) => {
        let input = textField.value;
        window.open(window.location.origin + `/api/search.php?search=${input}`);
    })

    textField.addEventListener('keydown', e => {
        if (e.code != 'Enter') {
            return
        }
        let input = textField.value;
        window.open(window.location.origin + `/api/search.php/?search=${input}`);
    });
}

// adding it to the search bar
let searchField = document.querySelector('.search>input');
let searchIcon = document.querySelector('.bar>i');

if (searchIcon != null && searchField != null) {
    console.log('added search functionality 1')
    addSearchFunctionality(searchField, searchIcon)
} else {
    console.log(`baka1`)
}


//TODO:
// []- search.php: (make them ordered by id)

// add_place
const addPlaceForm = document.querySelector('#add_place form');

addPlaceForm.addEventListener('submit', e => {
    e.preventDefault();
    fetch('./api/add_place.php', {
        method: 'post',
        body: new FormData(addPlaceForm)
    })
        .then(response => response.json())
        .then(jsoned => {
            console.log('Response as JSON:', jsoned);
            // console.log('Response as Text:', textData);

            if (jsoned['error']) {
                alert(`error: ${jsoned['msg']}`);
            } else {
                console.log('reloading!...');
                location.reload();
            }
        })
        .catch(err => {
            console.log(err);
        });
});


// dark mode:


const toggle = document.querySelector('.darkmode i');
let nightMode = "fa-solid fa-moon";
let dayMode = "fa-solid fa-lightbulb";
toggle.classList.add(...dayMode.split(' '));
let state = false;

toggle.addEventListener('click', e => {
    if (state) {
        // assume day mode as default?
        toggle.classList.remove(...nightMode.split(' '));
        toggle.classList.add(...dayMode.split(' '));
    } else {
        toggle.classList.remove(...dayMode.split(' '));
        toggle.classList.add(...nightMode.split(' '));
    }

    state = !state;

    document.querySelector('.page').classList.toggle('darkmode');
})


// redirecting the user after sending the form:

document.querySelector("#add_post form").addEventListener('submit', e => {
    location.reload();
})


function getCreateTogglable(previous = 0) {

    function createTogglable(toggles, cards) {
        // the premise:
        // toggles[i] switches the class of cards[i] from from hidden to shown and hides the previously shown card
        // TODO:
        // [/]- add dummy cards, and style them on top of each others
        // []- whenever you think you neeed external motivation to dive in and do a project
        // rememver that you can just dive and start coding the functionality and expect your intuition and flow state to
        // just kick in!

        for (let i = 0; i < toggles.length; i++) {
            toggles[i].addEventListener('click', e => {
                console.log(i);
                cards[previous].classList.add('hidden');
                cards[i].classList.remove('hidden');
                previous = i;
            })
        }
    }

    return createTogglable;
}

getCreateTogglable()(document.querySelectorAll('#navigation_bar > i'), document.querySelectorAll('#content > div'));


class PlacesSet extends PostSet {

    constructor(...args) {
        super(...args);
    }

    render() {
        console.log(this.api);
        console.log(this.content);


        // this is supposed to render this.posts to this.div
        // for testing reasons, it is just going to print from last post!
        console.log("rendering ...");
        if (this.content.length === this.lastPostIndex) {
            return;
        }

        console.log(this.content.slice(this.lastPostIndex, this.content.length));

        // actual rendering to page:
        console.log("rendering ...");

        // TODO: replace this outdated way of rendering with a newer, template-based method
        for (let post of this.content.slice(
            this.lastPostIndex,
            this.content.length
        )) {

            console.log(post);
            console.log(`post link: ../../views/post.php?id=${post['id']}`);

            let a = document.createElement('a');
            a.href = `../../views/place.php?id=${post['id']}`;

            let place = document.createElement('div')
            place.classList.add('userDiv')

            let username = document.createElement('p')
            username.classList.add('username')

            let photo = document.createElement('img')
            photo.classList.add('userImg')

            // description.innerHTML = post.user.description
            username.innerHTML = post.user.username
            // userImg.src = `/public/profiles/` + (post.user.picture ? post.user.username : 'user.png')
            // photo.src = `/public/places/${post.id}`;
            photo.src = `/public/profiles/` + (post.user.picture ? post.user.username : 'default/user.png')

            place.appendChild(photo)
            place.appendChild(username)

            let postContent = document.createElement('p')
            postContent.innerHTML = post.description || "no description!";
            postContent.classList.add('description');

            let postDate = document.createElement('p')
            postDate.innerHTML = post.createdAt || "date undefined!";
            postDate.classList.add('postDate')

            let img = document.createElement('img')
            img.src = `public/places/${post.id}`

            let postContainer = document.createElement('div');
            postContainer.classList.add('post')

            // adding the link

            postContainer.appendChild(place)
            postContainer.appendChild(postContent)
            postContainer.appendChild(img)
            postContainer.appendChild(postDate)
            postContainer.appendChild(document.createElement('hr'))

            // this.div.appendChild(postContainer)
            // TODO: I'm going to alter this rendering method
            a.appendChild(postContainer);
            this.div.appendChild(a);
        }

        this.lastPostIndex = this.content.length;

    }

}


class EventsSet extends PostSet {


    constructor(...args) {
        super(...args);
    }

    render() {
        console.log(this.api);
        console.log(this.content);


        // this is supposed to render this.posts to this.div
        // for testing reasons, it is just going to print from last post!
        console.log("rendering ...");
        if (this.content.length === this.lastPostIndex) {
            return;
        }

        console.log(this.content.slice(this.lastPostIndex, this.content.length));

        // actual rendering to page:
        console.log("rendering ...");

        // TODO: replace this outdated way of rendering with a newer, template-based method
        for (let post of this.content.slice(
            this.lastPostIndex,
            this.content.length
        )) {

            console.log(`post link: ../../views/event.php?id=${post['id']}`);

            let a = document.createElement('a');
            a.href = `../../views/event.php?id=${post['id']}`;

            let userDiv = document.createElement('div')
            userDiv.classList.add('userDiv')

            let username = document.createElement('p')
            username.classList.add('username')

            let userImg = document.createElement('img')
            userImg.classList.add('userImg')

            username.innerHTML = post.user.username
            // userImg.src = `/public/profiles/` + (post.user.picture ? post.user.username : 'user.png')
            userImg.src = `/public/profiles/` + (post.user.picture ? post.user.username : 'default/user.png')

            userDiv.appendChild(userImg)
            userDiv.appendChild(username)

            let postContent = document.createElement('p')
            postContent.innerHTML = post.description || "content undefined!";
            postContent.classList.add('postContent')

            let postDate = document.createElement('p')
            postDate.innerHTML = post.createdAt || "date undefined!";
            postDate.classList.add('postDate')

            let img = document.createElement('img')
            img.src = `public/events/${post.id}`

            let postContainer = document.createElement('div');
            postContainer.classList.add('post')


            let pdead = document.createElement('p');
            pdead.innerHTML = `will be held at: ${post['date']}`;
            pdead.classList.add('deadline')

            postContainer.appendChild(userDiv)
            postContainer.appendChild(postContent)
            postContainer.appendChild(img)
            postContainer.appendChild(postDate)
            postContainer.appendChild(document.createElement('hr'))
            postContainer.appendChild(pdead)

            // this.div.appendChild(postContainer)
            // TODO: I'm going to alter this rendering method
            a.appendChild(postContainer);
            this.div.appendChild(a);
        }

        this.lastPostIndex = this.content.length;

    }
}


let myPlaces = new PlacesSet(document.getElementsByClassName("realContent2")[0], "api/get_places.php");


let myEvents = new EventsSet(document.getElementsByClassName("realContent3")[0], "api/get_events.php");


// TODO:
// []- make the render function renders all posts form current index to tha last item
// no need to make the user wait for locally available data! - useless bottleneck!
if (container != null) {
    container.addEventListener("scroll", (e) => {
        // TODO:
        // []- when the event is triggered, stop listening for 3s, and then re-attach the event
        // to stop requesting more than one page's worth of posts if you happen to hit the bottom
        // twice or more before new posts render
        // []- (maybe) turn the trigger to: hovering over the last element, rather than scrolling down to the last element!
        if (
            // scrollTop = scrollHeight - offsetHeight
            container.scrollTop + container.offsetHeight >
            container.scrollHeight - 20
        ) {
            myPosts.fetchNext();
            myPosts.render();

            myPlaces.fetchNext();
            myPlaces.render();

            myEvents.fetchNext();
            myEvents.render();
        }
    });
}
