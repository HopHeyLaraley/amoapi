<!-- сейчас на эту страницу будет приходить ответ после интеграции -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="js/jquery-3.7.1.min.js"></script>
    <style>
        form{
            display: flex;
            flex-direction: column;
            width: 50%;
            margin: auto;
            align-items: center;
            padding-top: 50px;
        }

        form input, form select, form textarea{
            width: 100%;
            height: 30px;
            margin-bottom: 50px;
            font-size: 20px;
        }

        form select{
            text-align: center;
            font-size: 20px;
        }

        form textarea{
            resize: none;
            height: 80px;
        }

        form label{
            font-size: 30px;
            font-style: italic;
        }

        form button{
            font-size: 20px;
            padding: 10px 20px;
            border-radius: 10px;
            border: none;
            background-color: #5ca4f6;
        }
    </style>
</head>
<body>
    <form id="amoForm">
        <label for="">Имя</label>
        <input type="text" name="name" id="name">

        <label for="">Телефон</label>
        <input type="tel" name="phone" id="phone">

        <label for="">E-mail</label>
        <input type="email" name="mail" id="mail">

        <label for="">Город</label>
        <input type="text" name="city" id="city">

        <label for="">Услуга</label>
        <select name="service" id="service">
            <option value="diag">Диагностика</option>
            <option value="fix">Ремонт</option>
        </select>

        <label for="">Комментарий</label>
        <textarea name="comment" id="comment" ></textarea>

        <button type="submit">Отправить</button>
    </form>
    <script>
        $(document).ready(function(){
            $('#amoForm').on('submit', function(event){
                event.preventDefault();

                let data = {
                    name: $('#name').val(),
                    phone: $('#phone').val(),
                    mail: $('#mail').val(),
                    city: $('#city').val(),
                    service: $('#service').val(),
                    comment: $('#comment').val(),
                    
                };
                $.ajax({
                    url: 'https://localhost/handler.php',
                    method: 'POST',
                    data: data,
                    success: function(){
                        alert("Форма успешно отправлена");
                        console.log(data);
                        $('#name').val('');
                        $('#phone').val('');
                        $('#mail').val('');
                        $('#city').val('');
                        $('#service').val('');
                        $('#comment').val('');
                    },
                    error: function(a){
                        alert("error");
                        console.log(a);
                    }
                });
                
            })
        });
    </script>
</body>
</html>