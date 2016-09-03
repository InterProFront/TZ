<!DOCTYPE html>
<html>
<head>
    <title>Laravel</title>

    <script src="/js/lib/jquery.min.js"></script>
    <script src="/js/lib/lodash.min.js"></script>


    <script src="/js/addPopup.js"></script>
    <script src="/js/editPopup.js"></script>
    <script src="/js/removePopup.js"></script>


    <script src="/js/editMenu.js"></script>
    <link rel="stylesheet" href="/css/popup.css">

</head>
<body>
<div class="wrap" id="form">
    <img src="/1.jpg" alt="">
</div>

    <script type="text/template" id="new-mark">
        <div class="mark-icon"><%= numb %></div>
        <div class="form">
            <div class="row option">

            </div>
            <div class="row">
                <input type="text" data-name="title" class="input" placeholder="Заголовок">
            </div>
            <div class="row">
                <textarea placeholder="Текст" data-name="text" class="input"></textarea>
            </div>
            <div class="row">
                <button class="js_add_popup">Добавить</button>
            </div>
        </div>
    </script>


    <script type="text/template" id="mark-item">
        <div class="mark-icon hider"><%= numb %></div>
        <div class="form">
            <div class="row">
                <p class="title"><%=title%></p>
            </div>
            <div class="row">
                <p class="text"><%=text%></p>
            </div>
        </div>
    </script>
</body>
</html>
