<?php
include "views/partials/header.html";
?>

<link rel="stylesheet" href="views/style/home.css">
<title>Home Sweet Home!</title>
</head>

<body>
<div class="page">
    <section id="topbar">
        <a href="">
            <div class="home">

                <img src="" alt="" class="userImg">
            </div>
        </a>
        <div class="search">
            <div class="bar">
                <i class="fa-solid fa-magnifying-glass clickable"></i>
            </div>
            <input type="text" name="query" placeholder="lookup a landscape!"/>
        </div>
        <div id="functions">
            <div class="darkmode">
                <!--                <i class="fa-solid fa-moon clickable"></i>-->
                <i class="clickable"></i>
            </div>
            <div class="parameters"><i class="fa-solid fa-ellipsis clickable"></i></div>
        </div>
    </section>

    <div id="navigation_bar">
        <i class="clickable">posts</i>
        <i class="clickable">places</i>
        <i class="clickable">events</i>
    </div>

    <section id="content">
        <div class="realContent"></div>
        <!--        only one of theese three sections need to be toggled at time-->
        <div class="realContent2 hidden">
            <p>places</p>
        </div>

        <div class="realContent3 hidden">
            <p>events</p>
        </div>
    </section>

    <section id="footer">
        <div class="functionTrigger clickable">
            <i class="fa-sharp fa-solid fa-camera-retro"></i>
            <i class="fa-sharp fa-solid fa-plus"></i>
        </div>

        <div class="functionTrigger clickable">
            <i class="fa-solid fa-location-dot"></i>
            <i class="fa-sharp fa-solid fa-plus"></i>
        </div>

        <div class="functionTrigger clickable">
            <i class="fa-solid fa-calendar-days"></i>
            <i class="fa-sharp fa-solid fa-plus"></i>
        </div>
    </section>
    <div class="popup blog hidden" id="add_post">
        <div class="controls">
            <i class="fa-solid fa-xmark clickable"></i>
        </div>
        <div class="blog_content content">
            <form action="api/send_post.php" method="post" enctype="multipart/form-data">
                <textarea name="content" placeholder="say something"></textarea>

                <div class="file">
                    <input type="file" name="imgsrc">
                    <i class="fa-regular fa-image"></i>
                </div>

                <input type="submit" value="submit">
            </form>
        </div>

    </div>


    <div class="popup hidden" id="parameters">
        <div class="controls">
            <i class="fa-solid fa-xmark clickable"></i>
        </div>
        <div class="parameters_content content logout">
            <p>يـا الرايــــح ويـــن مسافـر تـروح تعيا و تــولي</p>
            <a href="api/logout.php">Logout</a>
        </div>
    </div>


    <div class="popup hidden" id="add_place">
        <div class="controls">
            <i class="fa-solid fa-xmark clickable"></i>
        </div>
        <div class="parameters_content content">
            <p>Add a place:</p>

            <!--            <form action="/api/add_place.php" method="post">-->
            <!--                <form action="/api/test.php" method="post" enctype="multipart/form-data">-->
            <form action="/api/add_place.php" method="post" enctype="multipart/form-data">
                <label for="name">Name:</label>
                <input type="text" name="name">

                <div class="coordinates">
                    <label for="latitude">Latitude</label>
                    <input type="text" name="latitude">
                    <label for="longitude">Longitude</label>
                    <input type="text" name="longitude">
                </div>

                <label for="description">Description</label>
                <textarea name="description" placeholder="describe the place"></textarea>


                <div class="file">
                    <input type="file" name="imgsrc">
                    <i class="fa-regular fa-image"></i>
                </div>


                <input type="submit" value="submit">
            </form>
        </div>
    </div>

    <div class="popup hidden" id="add_event">
        <div class="controls">
            <i class="fa-solid fa-xmark clickable"></i>
        </div>
        <div class="parameters_content content">
            <form action="api/add_event.php" method="post" enctype="multipart/form-data">
                <label for="name">name</label>
                <input type="text" name="name">

                <label for="place">place id: (look in places)</label>
                <input type="text" name="placeid">

                <label for="description">description</label>
                <textarea name="description" placeholder="say something"></textarea>

                <div class="file">
                    <input type="file" name="imgsrc">
                    <i class="fa-regular fa-image"></i>
                </div>

                <input type="datetime-local" name="date">

                <input type="submit" value="submit">

            </form>
        </div>
    </div>

    <div class="popup_background hidden"></div>
</div>


<!-- include views/scripts/home.js -->
<script src="views/scripts/home.js"></script>
<!-- this is for image preview before sending the post: -->
<script src="views/scripts/send_post.js"></script>
</body>

</html>