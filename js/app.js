function submitData() {
    $(document).ready(function (e) {
        let data = { // объект данных с формы
            login: $('#login').val(),
            password: $('#password').val(),
            confirm_password: $('#confirm_password').val(),
            email: $('#email').val(),
            name: $('#name').val(),
            action: $('#action').val()
        };

        document.querySelectorAll('span').forEach(el => { // очищаем сообщения об ошибках
            if (!el.classList.contains("header")) {
                el.innerText = "";
            }
        })

        $.ajax({
            url: 'function.php',
            type: 'post',
            dataType: 'json',
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(data),
            success: function (responce) {
                // действия в зависимости от того, что пришло в ответ
                if (responce.result == "Register Successful") {
                    alert("Пользователь успешно создан!");
                    window.location.reload();
                }
                else if (responce.result == "Login Successful") {
                    alert("Пользователь успешно авторизован!");
                    window.location.reload();
                }
                else if (responce.result == "error") {
                    for (let errorField in responce.text_error) {
                        document.getElementById(errorField + "_error").innerText = "* " + responce.text_error[errorField]; // добавляем сообщение об ошибке
                    }
                }
            }
        })
    })
    return false; // останавливаем обновление страницы (действие по умолчанию)
}