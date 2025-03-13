<!DOCTYPE html>
<html>
<head>
    <style>
        .alert {
            position: relative;
            padding: 20px;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: .25rem;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .alert-error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .alert-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background-color: #007bff;
        }
    </style>
</head>
<body>
    <div id="successAlert" class="alert alert-success" onmouseover="pauseTimer('success')" onmouseout="startTimer('success')">
        Success! This is a success alert.
        <div id="successProgress" class="alert-progress"></div>
    </div>
    <div id="errorAlert" class="alert alert-error" onmouseover="pauseTimer('error')" onmouseout="startTimer('error')">
        Error! This is an error alert.
        <div id="errorProgress" class="alert-progress"></div>
    </div>

    <script>
        var timers = {
            success: null,
            error: null
        };

        function startTimer(alertType) {
            var alertElement = document.getElementById(alertType + 'Alert');
            var progressElement = document.getElementById(alertType + 'Progress');

            timers[alertType] = setInterval(function() {
                var width = progressElement.offsetWidth;
                var alertWidth = alertElement.offsetWidth;

                if (width <= 0) {
                    clearInterval(timers[alertType]);
                    alertElement.style.display = 'none'; // Adicionado para esconder a div após 15 segundos
                } else {
                    progressElement.style.width = (width - (alertWidth / 10)) + 'px';
                }
            }, 1000);
        }

        function pauseTimer(alertType) {
            clearInterval(timers[alertType]);
        }

        startTimer('success');
        startTimer('error');
    </script>
</body>
</html>
